//
//  JTSImageViewController.m
//
//
//  Created by Jared Sinclair on 3/28/14.
//  Copyright (c) 2014 Nice Boy LLC. All rights reserved.
//

#import "JTSImageViewController.h"
#import "JTSSimpleImageDownloader.h"

///--------------------------------------------------------------------------------------------------------------------
/// Definitions
///--------------------------------------------------------------------------------------------------------------------

// Public Constants
CGFloat const JTSImageViewController_DefaultAlphaForBackgroundDimmingOverlay = 0.66f;
CGFloat const JTSImageViewController_DefaultBackgroundBlurRadius = 2.0f;

// Private Constants
CGFloat const JTSImageViewController_MinimumBackgroundScaling = 0.94f;
CGFloat const JTSImageViewController_TargetZoomForDoubleTap = 3.0f;
CGFloat const JTSImageViewController_MaxScalingForExpandingOffscreenStyleTransition = 1.25f;
CGFloat const JTSImageViewController_TransitionAnimationDuration = 0.3f;
CGFloat const JTSImageViewController_MinimumFlickDismissalVelocity = 800.0f;
CGFloat const JTSImageViewController_zoomScale = 1.0f;
CGFloat const JTSImageViewController_maximumZoomScale = 8.0f;

typedef struct {
    BOOL statusBarHiddenPriorToPresentation;
    UIStatusBarStyle statusBarStylePriorToPresentation;
    CGRect startingReferenceFrameForThumbnail;
    CGRect startingReferenceFrameForThumbnailInPresentingViewControllersOriginalOrientation;
    CGPoint startingReferenceCenterForThumbnail;
    UIInterfaceOrientation startingInterfaceOrientation;
    BOOL presentingViewControllerPresentedFromItsUnsupportedOrientation;
} JTSImageViewControllerStartingInfo;

typedef struct {
    BOOL isAnimatingAPresentationOrDismissal;
    BOOL isDismissing;
    BOOL isTransitioningFromInitialModalToInteractiveState;
    BOOL viewHasAppeared;
    BOOL isPresented;
    BOOL rotationTransformIsDirty;
    BOOL scrollViewIsAnimatingAZoom;
    BOOL imageIsBeingReadFromDisk;
    BOOL isManuallyResizingTheScrollViewFrame;
    BOOL imageDownloadFailed;
} JTSImageViewControllerFlags;

#define USE_DEBUG_SLOW_ANIMATIONS 0

///--------------------------------------------------------------------------------------------------------------------
/// Anonymous Category
///--------------------------------------------------------------------------------------------------------------------

@interface JTSImageViewController ()
<
UIScrollViewDelegate,
UITextViewDelegate,
UIViewControllerTransitioningDelegate,
UIGestureRecognizerDelegate
>

// General Info
@property (strong, nonatomic, readwrite) JTSImageInfo *imageInfo;
@property (strong, nonatomic, readwrite) UIImage *image;
@property (assign, nonatomic, readwrite) JTSImageViewControllerTransition transition;
@property (assign, nonatomic, readwrite) JTSImageViewControllerMode mode;
@property (assign, nonatomic, readwrite) JTSImageViewControllerBackgroundStyle backgroundStyle;
@property (assign, nonatomic) JTSImageViewControllerStartingInfo startingInfo;
@property (assign, nonatomic) JTSImageViewControllerFlags flags;

// Autorotation
@property (assign, nonatomic) UIInterfaceOrientation lastUsedOrientation;
@property (assign, nonatomic) CGAffineTransform currentSnapshotRotationTransform;

// Views
@property (strong, nonatomic) UIView *outerContainerForScrollView;
@property (strong, nonatomic) UIView *blurredSnapshotView;
@property (strong, nonatomic) UIView *blackBackdrop;
@property (strong, nonatomic) UIImageView *imageView;
@property (strong, nonatomic) UIScrollView *scrollView;

// Gesture Recognizers
@property (strong, nonatomic) UITapGestureRecognizer *singleTapperPhoto;
@property (strong, nonatomic) UITapGestureRecognizer *doubleTapperPhoto;
@property (strong, nonatomic) UIPanGestureRecognizer *panRecognizer;

// Image Downloading
@property (strong, nonatomic) NSURLSessionDataTask *imageDownloadDataTask;
@property (strong, nonatomic) NSTimer *downloadProgressTimer;

@end

///--------------------------------------------------------------------------------------------------------------------
/// Implementation
///--------------------------------------------------------------------------------------------------------------------

@implementation JTSImageViewController

#pragma mark - Public

- (instancetype)initWithImageInfo:(JTSImageInfo *)imageInfo
                             mode:(JTSImageViewControllerMode)mode
                  backgroundStyle:(JTSImageViewControllerBackgroundStyle)backgroundStyle {
    
    self = [super initWithNibName:nil bundle:nil];
    if (self) {
        _imageInfo = imageInfo;
        _currentSnapshotRotationTransform = CGAffineTransformIdentity;
        _mode = mode;
        _backgroundStyle = backgroundStyle;
        if (_mode == JTSImageViewControllerMode_Image) {
            [self setupImageAndDownloadIfNecessary:imageInfo];
        }
    }
    return self;
}

- (void)showFromViewController:(UIViewController *)viewController
                    transition:(JTSImageViewControllerTransition)transition {
    
    [self setTransition:transition];
    
    _startingInfo.statusBarHiddenPriorToPresentation = [UIApplication sharedApplication].statusBarHidden;
    _startingInfo.statusBarStylePriorToPresentation = [UIApplication sharedApplication].statusBarStyle;
    
    if (self.mode == JTSImageViewControllerMode_Image) {
            [self showImageViewerByExpandingFromOriginalPositionFromViewController:viewController];
    }
}

- (void)dismiss:(BOOL)animated {
    
    if (_flags.isPresented == NO) {
        return;
    }
    
    _flags.isPresented = NO;
    
    if (self.mode == JTSImageViewControllerMode_Image) {
        
        BOOL startingRectForThumbnailIsNonZero = (CGRectEqualToRect(CGRectZero, _startingInfo.startingReferenceFrameForThumbnail) == NO);
        BOOL useCollapsingThumbnailStyle = (startingRectForThumbnailIsNonZero
                                            && self.image != nil);
        if (useCollapsingThumbnailStyle) {
            [self dismissByCollapsingImageBackToOriginalPosition];
        } else {
            [self dismissByExpandingImageToOffscreenPosition];
        }
    }
}

#pragma mark - NSObject

- (void)dealloc {
    [_imageDownloadDataTask cancel];
}

#pragma mark - UIViewController

- (NSUInteger)supportedInterfaceOrientations {
    
    /*
     iOS 8 changes the behavior of autorotation when presenting a
     modal view controller whose supported orientations outnumber
     the orientations of the presenting view controller.
     
     E.g., when a portrait-only iPhone view controller presents
     JTSImageViewController while the **device** is oriented in
     landscape, on iOS 8 the modal view controller presents straight
     into landscape, whereas on iOS 7 the interface orientation
     of the presenting view controller is preserved.
     
     In my judgement the iOS 7 behavior is preferable. It also simplifies
     the rotation corrections during presentation. - August 31, 2014 JTS.
     */
    
    NSUInteger mask;
    
    if (self.flags.viewHasAppeared == NO) {
        switch ([UIApplication sharedApplication].statusBarOrientation) {
            case UIInterfaceOrientationLandscapeLeft:
                mask = UIInterfaceOrientationMaskLandscapeLeft;
                break;
            case UIInterfaceOrientationLandscapeRight:
                mask = UIInterfaceOrientationMaskLandscapeRight;
                break;
            case UIInterfaceOrientationPortrait:
                mask = UIInterfaceOrientationMaskPortrait;
                break;
            case UIInterfaceOrientationPortraitUpsideDown:
                mask = UIInterfaceOrientationMaskPortraitUpsideDown;
                break;
            default:
                mask = UIInterfaceOrientationPortrait;
                break;
        }
    }
    else if ([UIDevice currentDevice].userInterfaceIdiom == UIUserInterfaceIdiomPad) {
        mask = UIInterfaceOrientationMaskAll;
    } else {
        mask = UIInterfaceOrientationMaskAllButUpsideDown;
    }
    return mask;
}

- (BOOL)shouldAutorotate {
    return (_flags.isAnimatingAPresentationOrDismissal == NO);
}

- (BOOL)prefersStatusBarHidden {
    
    if (_flags.isPresented || _flags.isTransitioningFromInitialModalToInteractiveState) {
        return YES;
    }
    
    return _startingInfo.statusBarHiddenPriorToPresentation;
}

- (UIStatusBarAnimation)preferredStatusBarUpdateAnimation {
    return UIStatusBarAnimationFade;
}

- (UIModalTransitionStyle)modalTransitionStyle {
    return UIModalTransitionStyleCrossDissolve;
}

- (UIStatusBarStyle)preferredStatusBarStyle {
    return _startingInfo.statusBarStylePriorToPresentation;
}

- (void)viewDidLoad {
    [super viewDidLoad];
    if (self.mode == JTSImageViewControllerMode_Image) {
        [self viewDidLoadForImageMode];
    }
}

- (void)viewDidLayoutSubviews {
    [self updateLayoutsForCurrentOrientation];
}

- (void)viewWillAppear:(BOOL)animated {
    [super viewWillAppear:animated];
    if (self.lastUsedOrientation != [UIApplication sharedApplication].statusBarOrientation) {
        self.lastUsedOrientation = [UIApplication sharedApplication].statusBarOrientation;
        _flags.rotationTransformIsDirty = YES;
        [self updateLayoutsForCurrentOrientation];
    }
}

- (void)viewDidAppear:(BOOL)animated {
    [super viewDidAppear:animated];
    _flags.viewHasAppeared = YES;
}

- (void)willRotateToInterfaceOrientation:(UIInterfaceOrientation)toInterfaceOrientation duration:(NSTimeInterval)duration {
    [self setLastUsedOrientation:toInterfaceOrientation];
    _flags.rotationTransformIsDirty = YES;
}

- (void)willAnimateRotationToInterfaceOrientation:(UIInterfaceOrientation)toInterfaceOrientation duration:(NSTimeInterval)duration {
    [self updateLayoutsForCurrentOrientation];
    [self updateDimmingViewForCurrentZoomScale:NO];
}

#pragma mark - Setup

- (void)setupImageAndDownloadIfNecessary:(JTSImageInfo *)imageInfo {
    if (imageInfo.image) {
        [self setImage:imageInfo.image];
    }
    else {
        
        [self setImage:imageInfo.placeholderImage];
        
        BOOL fromDisk = [imageInfo.imageURL.absoluteString hasPrefix:@"file://"];
        _flags.imageIsBeingReadFromDisk = fromDisk;
        
        __weak JTSImageViewController *weakSelf = self;
        NSURLSessionDataTask *task = [JTSSimpleImageDownloader downloadImageForURL:imageInfo.imageURL canonicalURL:imageInfo.canonicalImageURL completion:^(UIImage *image) {
            if (image) {
                if (weakSelf.isViewLoaded) {
                    [weakSelf updateInterfaceWithImage:image];
                } else {
                    [weakSelf setImage:image];
                }
            } else if (weakSelf.image == nil) {
                _flags.imageDownloadFailed = YES;
                if (_flags.isPresented && _flags.isAnimatingAPresentationOrDismissal == NO) {
                    [weakSelf dismiss:YES];
                }
                // If we're still presenting, at the end of presentation we'll auto dismiss.
            }
        }];
        
        [self setImageDownloadDataTask:task];
    }
}

- (void)viewDidLoadForImageMode {
    
    [self.view setAutoresizingMask:UIViewAutoresizingFlexibleHeight|UIViewAutoresizingFlexibleWidth];
    [self.view setBackgroundColor:[UIColor blackColor]];
    self.blackBackdrop = [[UIView alloc] initWithFrame:CGRectInset(self.view.bounds, -512, -512)];
    [self.blackBackdrop setBackgroundColor:[UIColor blackColor]];
    [self.blackBackdrop setAlpha:0];
    [self.view addSubview:self.blackBackdrop];
    
    self.scrollView = [[UIScrollView alloc] initWithFrame:self.view.bounds];
    [self.scrollView setBackgroundColor:[UIColor clearColor]];
    self.scrollView.delegate = self;
    self.scrollView.zoomScale = JTSImageViewController_zoomScale;
    self.scrollView.maximumZoomScale = JTSImageViewController_maximumZoomScale;
    self.scrollView.showsHorizontalScrollIndicator = NO;
    self.scrollView.showsVerticalScrollIndicator = NO;
    self.scrollView.scrollEnabled = NO;
    self.scrollView.bounces = NO;
    self.scrollView.isAccessibilityElement = YES;
    self.scrollView.accessibilityLabel = self.accessibilityLabel;
    [self.view addSubview:self.scrollView];
    
    CGRect referenceFrameInWindow = [self.imageInfo.referenceView convertRect:self.imageInfo.referenceRect toView:nil];
    CGRect referenceFrameInMyView = [self.view convertRect:referenceFrameInWindow fromView:nil];
    
    self.imageView = [[UIImageView alloc] initWithFrame:referenceFrameInMyView];
    self.imageView.contentMode = UIViewContentModeScaleAspectFill;
    self.imageView.userInteractionEnabled = YES;
    self.imageView.isAccessibilityElement = NO;
    self.imageView.clipsToBounds = YES;
    
    // We'll add the image view to either the scroll view
    // or the parent view, based on the transition style
    // used in the "show" method.
    // After that transition completes, the image view will be
    // added to the scroll view.
    
    [self setupImageModeGestureRecognizers];
    
    if (self.image) {
        [self updateInterfaceWithImage:self.image];
    }
}

- (void)setupImageModeGestureRecognizers {
    
    UITapGestureRecognizer *doubleTapper = nil;
    doubleTapper = [[UITapGestureRecognizer alloc] initWithTarget:self action:@selector(imageDoubleTapped:)];
    doubleTapper.numberOfTapsRequired = 2;
    doubleTapper.delegate = self;
    self.doubleTapperPhoto = doubleTapper;
    
    UITapGestureRecognizer *singleTapper = nil;
    singleTapper = [[UITapGestureRecognizer alloc] initWithTarget:self action:@selector(imageSingleTapped:)];
    [singleTapper requireGestureRecognizerToFail:doubleTapper];
    singleTapper.delegate = self;
    self.singleTapperPhoto = singleTapper;
    
    UIPanGestureRecognizer *panner = [[UIPanGestureRecognizer alloc] init];
    [panner setDelegate:self];
    [self.scrollView addGestureRecognizer:panner];
    [self setPanRecognizer:panner];
    
    [self.view addGestureRecognizer:singleTapper];
    [self.view addGestureRecognizer:doubleTapper];
}

#pragma mark - Presentation

- (void)showImageViewerByExpandingFromOriginalPositionFromViewController:(UIViewController *)viewController {
    
    _flags.isAnimatingAPresentationOrDismissal = YES;
    [self.view setUserInteractionEnabled:NO];
    
    if (self.backgroundStyle == JTSImageViewControllerBackgroundStyle_ScaledDimmedBlurred) {
        [self.blurredSnapshotView setAlpha:0];
    }
    
    _startingInfo.startingInterfaceOrientation = [UIApplication sharedApplication].statusBarOrientation;
    
    self.lastUsedOrientation = [UIApplication sharedApplication].statusBarOrientation;
    CGRect referenceFrameInWindow = [self.imageInfo.referenceView convertRect:self.imageInfo.referenceRect toView:nil];
    
    _startingInfo.startingReferenceFrameForThumbnailInPresentingViewControllersOriginalOrientation = [self.view convertRect:referenceFrameInWindow fromView:nil];
    
    if (self.imageInfo.contentMode) {
        self.imageView.contentMode = self.imageInfo.contentMode;
    }
    
    // This will be moved into the scroll view after
    // the transition finishes.
    [self.view addSubview:self.imageView];
    
    [viewController presentViewController:self animated:NO completion:^{
        
        if ([UIApplication sharedApplication].statusBarOrientation != _startingInfo.startingInterfaceOrientation) {
            _startingInfo.presentingViewControllerPresentedFromItsUnsupportedOrientation = YES;
        }
        
        
        CGRect referenceFrameInMyView = [self.view convertRect:referenceFrameInWindow fromView:nil];
        _startingInfo.startingReferenceFrameForThumbnail = referenceFrameInMyView;
        [self.imageView setFrame:referenceFrameInMyView];
        [self updateScrollViewAndImageViewForCurrentMetrics];
        
        BOOL mustRotateDuringTransition = ([UIApplication sharedApplication].statusBarOrientation != _startingInfo.startingInterfaceOrientation);
        if (mustRotateDuringTransition) {
            [self updateScrollViewAndImageViewForCurrentMetrics];
            CGPoint centerInRect = CGPointMake(_startingInfo.startingReferenceFrameForThumbnail.origin.x
                                               +_startingInfo.startingReferenceFrameForThumbnail.size.width/2.0f,
                                               _startingInfo.startingReferenceFrameForThumbnail.origin.y
                                               +_startingInfo.startingReferenceFrameForThumbnail.size.height/2.0f);
            [self.imageView setCenter:centerInRect];
        }
        
        CGFloat duration = JTSImageViewController_TransitionAnimationDuration;
        if (USE_DEBUG_SLOW_ANIMATIONS == 1) {
            duration *= 4;
        }
        
        __weak JTSImageViewController *weakSelf = self;
        
        // Have to dispatch ahead two runloops,
        // or else the image view changes above won't be
        // committed prior to the animations below.
        //
        // Dispatching only one runloop ahead doesn't fix
        // the issue on certain devices.
        //
        // This issue also seems to be triggered by only
        // certain kinds of interactions with certain views,
        // especially when a UIButton is the reference
        // for the JTSImageInfo.
        //
        dispatch_async(dispatch_get_main_queue(), ^{
            dispatch_async(dispatch_get_main_queue(), ^{
                
                [UIView
                 animateWithDuration:duration
                 delay:0
                 options:UIViewAnimationOptionBeginFromCurrentState | UIViewAnimationOptionCurveEaseInOut
                 animations:^{
                     
                     _flags.isTransitioningFromInitialModalToInteractiveState = YES;
                    
                     if (weakSelf.backgroundStyle == JTSImageViewControllerBackgroundStyle_ScaledDimmedBlurred) {
                         [weakSelf.blurredSnapshotView setAlpha:1];
                     }

                     [weakSelf.blackBackdrop setAlpha:self.alphaForBackgroundDimmingOverlay];
                     
                     if (mustRotateDuringTransition) {
                         [weakSelf.imageView setTransform:CGAffineTransformIdentity];
                     }
                     
                     CGRect endFrameForImageView;
                     if (weakSelf.image) {
                         endFrameForImageView = [weakSelf resizedFrameForAutorotatingImageView:weakSelf.image.size];
                     } else {
                         endFrameForImageView = [weakSelf resizedFrameForAutorotatingImageView:weakSelf.imageInfo.referenceRect.size];
                     }
                     [weakSelf.imageView setFrame:endFrameForImageView];
                     
                     CGPoint endCenterForImageView = CGPointMake(weakSelf.view.bounds.size.width/2.0f, weakSelf.view.bounds.size.height/2.0f);
                     [weakSelf.imageView setCenter:endCenterForImageView];
                     
                 } completion:^(BOOL finished) {
                     
                     _flags.isManuallyResizingTheScrollViewFrame = YES;
                     [weakSelf.scrollView setFrame:weakSelf.view.bounds];
                     _flags.isManuallyResizingTheScrollViewFrame = NO;
                     [weakSelf.scrollView addSubview:weakSelf.imageView];
                     
                     _flags.isTransitioningFromInitialModalToInteractiveState = NO;
                     _flags.isAnimatingAPresentationOrDismissal = NO;
                     _flags.isPresented = YES;
                     
                     [weakSelf updateScrollViewAndImageViewForCurrentMetrics];
                     
                     if (_flags.imageDownloadFailed) {
                         [weakSelf dismiss:YES];
                     } else {
                         [weakSelf.view setUserInteractionEnabled:YES];
                     }
                 }];
            });
        });
    }];
}

- (void)showImageViewerByScalingDownFromOffscreenPositionWithViewController:(UIViewController *)viewController {
    
    _flags.isAnimatingAPresentationOrDismissal = YES;
    [self.view setUserInteractionEnabled:NO];
    
    if (self.backgroundStyle == JTSImageViewControllerBackgroundStyle_ScaledDimmedBlurred) {
        [self.blurredSnapshotView setAlpha:0];
    }
    
    _startingInfo.startingInterfaceOrientation = [UIApplication sharedApplication].statusBarOrientation;
    [self setLastUsedOrientation:[UIApplication sharedApplication].statusBarOrientation];
    CGRect referenceFrameInWindow = [self.imageInfo.referenceView convertRect:self.imageInfo.referenceRect toView:nil];
    _startingInfo.startingReferenceFrameForThumbnailInPresentingViewControllersOriginalOrientation = [self.view convertRect:referenceFrameInWindow fromView:nil];
    
    [self.scrollView addSubview:self.imageView];
    
    [self.scrollView setAlpha:0];
    
    [viewController presentViewController:self animated:NO completion:^{
        
        if ([UIApplication sharedApplication].statusBarOrientation != _startingInfo.startingInterfaceOrientation) {
            _startingInfo.presentingViewControllerPresentedFromItsUnsupportedOrientation = YES;
        }
        
        [self.scrollView setFrame:self.view.bounds];
        [self updateScrollViewAndImageViewForCurrentMetrics];
        CGFloat scaling = JTSImageViewController_MaxScalingForExpandingOffscreenStyleTransition;
        [self.scrollView setTransform:CGAffineTransformMakeScale(scaling, scaling)];
        
        CGFloat duration = JTSImageViewController_TransitionAnimationDuration;
        if (USE_DEBUG_SLOW_ANIMATIONS == 1) {
            duration *= 4;
        }
        
        __weak JTSImageViewController *weakSelf = self;
        
        // Have to dispatch to the next runloop,
        // or else the image view changes above won't be
        // committed prior to the animations below.
        dispatch_async(dispatch_get_main_queue(), ^{
            
            [UIView
             animateWithDuration:duration
             delay:0
             options:UIViewAnimationOptionBeginFromCurrentState | UIViewAnimationOptionCurveEaseInOut
             animations:^{
                 
                 _flags.isTransitioningFromInitialModalToInteractiveState = YES;
                 
                 if (weakSelf.backgroundStyle == JTSImageViewControllerBackgroundStyle_ScaledDimmedBlurred) {
                     [weakSelf.blurredSnapshotView setAlpha:1];
                 }
                 
                 [weakSelf.blackBackdrop setAlpha:self.alphaForBackgroundDimmingOverlay];
                 
                 [weakSelf.scrollView setAlpha:1.0f];
                 [weakSelf.scrollView setTransform:CGAffineTransformIdentity];
                 
             } completion:^(BOOL finished) {
                 _flags.isTransitioningFromInitialModalToInteractiveState = NO;
                 _flags.isAnimatingAPresentationOrDismissal = NO;
                 [weakSelf.view setUserInteractionEnabled:YES];
                 _flags.isPresented = YES;
                 if (_flags.imageDownloadFailed) {
                     [weakSelf dismiss:YES];
                 }
             }];
        });
    }];
}

- (void)showAltTextFromViewController:(UIViewController *)viewController {
    
    _flags.isAnimatingAPresentationOrDismissal = YES;
    [self.view setUserInteractionEnabled:NO];
    
    if (self.backgroundStyle == JTSImageViewControllerBackgroundStyle_ScaledDimmedBlurred) {
        [self.blurredSnapshotView setAlpha:0];
    }
    
    _startingInfo.startingInterfaceOrientation = [UIApplication sharedApplication].statusBarOrientation;
    self.lastUsedOrientation = [UIApplication sharedApplication].statusBarOrientation;
    CGRect referenceFrameInWindow = [self.imageInfo.referenceView convertRect:self.imageInfo.referenceRect toView:nil];
    _startingInfo.startingReferenceFrameForThumbnailInPresentingViewControllersOriginalOrientation = [self.view convertRect:referenceFrameInWindow fromView:nil];
    
    __weak JTSImageViewController *weakSelf = self;
    
    [viewController presentViewController:weakSelf animated:NO completion:^{
        
        if ([UIApplication sharedApplication].statusBarOrientation != _startingInfo.startingInterfaceOrientation) {
            _startingInfo.presentingViewControllerPresentedFromItsUnsupportedOrientation = YES;
        }
        
        CGFloat duration = JTSImageViewController_TransitionAnimationDuration;
        if (USE_DEBUG_SLOW_ANIMATIONS == 1) {
            duration *= 4;
        }
        
        // Have to dispatch to the next runloop,
        // or else the image view changes above won't be
        // committed prior to the animations below.
        dispatch_async(dispatch_get_main_queue(), ^{
            
            [UIView
             animateWithDuration:duration
             delay:0
             options:UIViewAnimationOptionBeginFromCurrentState | UIViewAnimationOptionCurveEaseInOut
             animations:^{
                 
                 _flags.isTransitioningFromInitialModalToInteractiveState = YES;
                 
                 if (weakSelf.backgroundStyle == JTSImageViewControllerBackgroundStyle_ScaledDimmedBlurred) {
                     [weakSelf.blurredSnapshotView setAlpha:1];
                 }
                 
                 [weakSelf.blackBackdrop setAlpha:self.alphaForBackgroundDimmingOverlay];
                 
             } completion:^(BOOL finished) {
                 
                 _flags.isTransitioningFromInitialModalToInteractiveState = NO;
                 _flags.isAnimatingAPresentationOrDismissal = NO;
                 [weakSelf.view setUserInteractionEnabled:YES];
                 _flags.isPresented = YES;
             }];
        });
    }];
}

#pragma mark - Options Delegate Convenience

- (CGFloat)alphaForBackgroundDimmingOverlay {
    
    CGFloat alpha = JTSImageViewController_DefaultAlphaForBackgroundDimmingOverlay;
    return alpha;
}

- (CGFloat)backgroundBlurRadius {
    
    CGFloat blurRadius = JTSImageViewController_DefaultBackgroundBlurRadius;
    return blurRadius;
}

- (UIColor *)backgroundColorForImageView {
    
    UIColor *backgroundColor = [UIColor clearColor];
    return backgroundColor;
}

#pragma mark - Dismissal

- (void)dismissByCollapsingImageBackToOriginalPosition {
    
    [self.view setUserInteractionEnabled:NO];
    _flags.isAnimatingAPresentationOrDismissal = YES;
    _flags.isDismissing = YES;
    
    CGRect imageFrame = [self.view convertRect:self.imageView.frame fromView:self.scrollView];
    self.imageView.autoresizingMask = UIViewAutoresizingNone;
    [self.imageView setTransform:CGAffineTransformIdentity];
    [self.imageView.layer setTransform:CATransform3DIdentity];
    [self.imageView removeFromSuperview];
    [self.imageView setFrame:imageFrame];
    [self.view addSubview:self.imageView];
    [self.scrollView removeFromSuperview];
    [self setScrollView:nil];
    
    __weak JTSImageViewController *weakSelf = self;
    
    // Have to dispatch after or else the image view changes above won't be
    // committed prior to the animations below. A single dispatch_async(dispatch_get_main_queue()
    // wouldn't work under certain scrolling conditions, so it has to be an ugly
    // two runloops ahead.
    dispatch_async(dispatch_get_main_queue(), ^{
        dispatch_async(dispatch_get_main_queue(), ^{
            
            CGFloat duration = JTSImageViewController_TransitionAnimationDuration;
            if (USE_DEBUG_SLOW_ANIMATIONS == 1) {
                duration *= 4;
            }
            
            [UIView animateWithDuration:duration delay:0 options:UIViewAnimationOptionBeginFromCurrentState | UIViewAnimationOptionCurveEaseInOut animations:^{
                
                [weakSelf.blackBackdrop setAlpha:0];
                
                if (weakSelf.backgroundStyle == JTSImageViewControllerBackgroundStyle_ScaledDimmedBlurred) {
                    [weakSelf.blurredSnapshotView setAlpha:0];
                }
                
                BOOL mustRotateDuringTransition = ([UIApplication sharedApplication].statusBarOrientation != _startingInfo.startingInterfaceOrientation);
                if (mustRotateDuringTransition) {
                    CGRect newEndingRect;
                    CGPoint centerInRect;
                    if (_startingInfo.presentingViewControllerPresentedFromItsUnsupportedOrientation) {
                        newEndingRect = _startingInfo.startingReferenceFrameForThumbnailInPresentingViewControllersOriginalOrientation;
                    } else {
                        newEndingRect = _startingInfo.startingReferenceFrameForThumbnail;
                    }
                    [weakSelf.imageView setFrame:newEndingRect];
                    weakSelf.imageView.transform = weakSelf.currentSnapshotRotationTransform;
                    [weakSelf.imageView setCenter:centerInRect];
                } else {
                    if (_startingInfo.presentingViewControllerPresentedFromItsUnsupportedOrientation) {
                        [weakSelf.imageView setFrame:_startingInfo.startingReferenceFrameForThumbnailInPresentingViewControllersOriginalOrientation];
                    } else {
                        [weakSelf.imageView setFrame:_startingInfo.startingReferenceFrameForThumbnail];
                    }
                }
            } completion:^(BOOL finished) {
                
                // Needed if dismissing from a different orientation then the one we started with
                
                [weakSelf.presentingViewController dismissViewControllerAnimated:NO completion:nil];
            }];
        });
    });
}

- (void)dismissByExpandingImageToOffscreenPosition {
    
    [self.view setUserInteractionEnabled:NO];
    _flags.isAnimatingAPresentationOrDismissal = YES;
    _flags.isDismissing = YES;
    
    __weak JTSImageViewController *weakSelf = self;
    
    CGFloat duration = JTSImageViewController_TransitionAnimationDuration;
    if (USE_DEBUG_SLOW_ANIMATIONS == 1) {
        duration *= 4;
    }
    
    [UIView animateWithDuration:duration delay:0 options:UIViewAnimationOptionBeginFromCurrentState | UIViewAnimationOptionCurveEaseInOut animations:^{
        [weakSelf.blackBackdrop setAlpha:0];
        if (weakSelf.backgroundStyle == JTSImageViewControllerBackgroundStyle_ScaledDimmedBlurred) {
            [weakSelf.blurredSnapshotView setAlpha:0];
        }
        [weakSelf.scrollView setAlpha:0];
        CGFloat scaling = JTSImageViewController_MaxScalingForExpandingOffscreenStyleTransition;
        [weakSelf.scrollView setTransform:CGAffineTransformMakeScale(scaling, scaling)];
           } completion:^(BOOL finished) {
        [weakSelf.presentingViewController dismissViewControllerAnimated:NO completion:nil];
    }];
}

#pragma mark - Interface Updates

- (void)updateInterfaceWithImage:(UIImage *)image {
    
    if (image) {
        [self setImage:image];
        [self.imageView setImage:image];
        
        self.imageView.backgroundColor = [self backgroundColorForImageView];
    }
}

- (void)updateLayoutsForCurrentOrientation {
    
    if (self.mode == JTSImageViewControllerMode_Image) {
        [self updateScrollViewAndImageViewForCurrentMetrics];
    }
    
    CGAffineTransform transform = CGAffineTransformIdentity;
    
    if (_startingInfo.startingInterfaceOrientation == UIInterfaceOrientationPortrait) {
        switch ([UIApplication sharedApplication].statusBarOrientation) {
            case UIInterfaceOrientationLandscapeLeft:
                transform = CGAffineTransformMakeRotation(M_PI/2.0f);
                break;
            case UIInterfaceOrientationLandscapeRight:
                transform = CGAffineTransformMakeRotation(-M_PI/2.0f);
                break;
            case UIInterfaceOrientationPortrait:
                transform = CGAffineTransformIdentity;
                break;
            case UIInterfaceOrientationPortraitUpsideDown:
                transform = CGAffineTransformMakeRotation(M_PI);
                break;
            default:
                break;
        }
    }
    else if (_startingInfo.startingInterfaceOrientation == UIInterfaceOrientationPortraitUpsideDown) {
        switch ([UIApplication sharedApplication].statusBarOrientation) {
            case UIInterfaceOrientationLandscapeLeft:
                transform = CGAffineTransformMakeRotation(-M_PI/2.0f);
                break;
            case UIInterfaceOrientationLandscapeRight:
                transform = CGAffineTransformMakeRotation(M_PI/2.0f);
                break;
            case UIInterfaceOrientationPortrait:
                transform = CGAffineTransformMakeRotation(M_PI);
                break;
            case UIInterfaceOrientationPortraitUpsideDown:
                transform = CGAffineTransformIdentity;
                break;
            default:
                break;
        }
    }
    else if (_startingInfo.startingInterfaceOrientation == UIInterfaceOrientationLandscapeLeft) {
        switch ([UIApplication sharedApplication].statusBarOrientation) {
            case UIInterfaceOrientationLandscapeLeft:
                transform = CGAffineTransformIdentity;
                break;
            case UIInterfaceOrientationLandscapeRight:
                transform = CGAffineTransformMakeRotation(M_PI);
                break;
            case UIInterfaceOrientationPortrait:
                transform = CGAffineTransformMakeRotation(-M_PI/2.0f);
                break;
            case UIInterfaceOrientationPortraitUpsideDown:
                transform = CGAffineTransformMakeRotation(M_PI/2.0f);
                break;
            default:
                break;
        }
    }
    else if (_startingInfo.startingInterfaceOrientation == UIInterfaceOrientationLandscapeRight) {
        switch ([UIApplication sharedApplication].statusBarOrientation) {
            case UIInterfaceOrientationLandscapeLeft:
                transform = CGAffineTransformMakeRotation(M_PI);
                break;
            case UIInterfaceOrientationLandscapeRight:
                transform = CGAffineTransformIdentity;
                break;
            case UIInterfaceOrientationPortrait:
                transform = CGAffineTransformMakeRotation(M_PI/2.0f);
                break;
            case UIInterfaceOrientationPortraitUpsideDown:
                transform = CGAffineTransformMakeRotation(-M_PI/2.0f);
                break;
            default:
                break;
        }
    }
    
    if (_flags.rotationTransformIsDirty) {
        _flags.rotationTransformIsDirty = NO;
        self.currentSnapshotRotationTransform = transform;
        if (_flags.isPresented) {
            if (self.mode == JTSImageViewControllerMode_Image) {
                self.scrollView.frame = self.view.bounds;
            }
        }
    }
}

- (void)updateScrollViewAndImageViewForCurrentMetrics {
    
    if (_flags.isAnimatingAPresentationOrDismissal == NO) {
        _flags.isManuallyResizingTheScrollViewFrame = YES;
        self.scrollView.frame = self.view.bounds;
        _flags.isManuallyResizingTheScrollViewFrame = NO;
    }
    
    BOOL usingOriginalPositionTransition = (self.transition == JTSImageViewControllerTransition_FromOriginalPosition);
    
    BOOL suppressAdjustments = (usingOriginalPositionTransition && _flags.isAnimatingAPresentationOrDismissal);
    
    if (suppressAdjustments == NO) {
        if (self.image) {
            [self.imageView setFrame:[self resizedFrameForAutorotatingImageView:self.image.size]];
        } else {
            [self.imageView setFrame:[self resizedFrameForAutorotatingImageView:self.imageInfo.referenceRect.size]];
        }
        self.scrollView.contentSize = self.imageView.frame.size;
        self.scrollView.contentInset = [self contentInsetForScrollView:self.scrollView.zoomScale];
    }
}

- (UIEdgeInsets)contentInsetForScrollView:(CGFloat)targetZoomScale {
    UIEdgeInsets inset = UIEdgeInsetsZero;
    CGFloat boundsHeight = self.scrollView.bounds.size.height;
    CGFloat boundsWidth = self.scrollView.bounds.size.width;
    CGFloat contentHeight = (self.image.size.height > 0) ? self.image.size.height : boundsHeight;
    CGFloat contentWidth = (self.image.size.width > 0) ? self.image.size.width : boundsWidth;
    CGFloat minContentHeight;
    CGFloat minContentWidth;
    if (contentHeight > contentWidth) {
        if (boundsHeight/boundsWidth < contentHeight/contentWidth) {
            minContentHeight = boundsHeight;
            minContentWidth = contentWidth * (minContentHeight / contentHeight);
        } else {
            minContentWidth = boundsWidth;
            minContentHeight = contentHeight * (minContentWidth / contentWidth);
        }
    } else {
        if (boundsWidth/boundsHeight < contentWidth/contentHeight) {
            minContentWidth = boundsWidth;
            minContentHeight = contentHeight * (minContentWidth / contentWidth);
        } else {
            minContentHeight = boundsHeight;
            minContentWidth = contentWidth * (minContentHeight / contentHeight);
        }
    }
    CGFloat myHeight = self.view.bounds.size.height;
    CGFloat myWidth = self.view.bounds.size.width;
    minContentWidth *= targetZoomScale;
    minContentHeight *= targetZoomScale;
    if (minContentHeight > myHeight && minContentWidth > myWidth) {
        inset = UIEdgeInsetsZero;
    } else {
        CGFloat verticalDiff = boundsHeight - minContentHeight;
        CGFloat horizontalDiff = boundsWidth - minContentWidth;
        verticalDiff = (verticalDiff > 0) ? verticalDiff : 0;
        horizontalDiff = (horizontalDiff > 0) ? horizontalDiff : 0;
        inset.top = verticalDiff/2.0f;
        inset.bottom = verticalDiff/2.0f;
        inset.left = horizontalDiff/2.0f;
        inset.right = horizontalDiff/2.0f;
    }
    return inset;
}

- (CGRect)resizedFrameForAutorotatingImageView:(CGSize)imageSize {
    CGRect frame = self.view.bounds;
    CGFloat screenWidth = frame.size.width * self.scrollView.zoomScale;
    CGFloat screenHeight = frame.size.height * self.scrollView.zoomScale;
    CGFloat targetWidth = screenWidth;
    CGFloat targetHeight = screenHeight;
    CGFloat nativeHeight = screenHeight;
    CGFloat nativeWidth = screenWidth;
    if (imageSize.width > 0 && imageSize.height > 0) {
        nativeHeight = (imageSize.height > 0) ? imageSize.height : screenHeight;
        nativeWidth = (imageSize.width > 0) ? imageSize.width : screenWidth;
    }
    if (nativeHeight > nativeWidth) {
        if (screenHeight/screenWidth < nativeHeight/nativeWidth) {
            targetWidth = screenHeight / (nativeHeight / nativeWidth);
        } else {
            targetHeight = screenWidth / (nativeWidth / nativeHeight);
        }
    } else {
        if (screenWidth/screenHeight < nativeWidth/nativeHeight) {
            targetHeight = screenWidth / (nativeWidth / nativeHeight);
        } else {
            targetWidth = screenHeight / (nativeHeight / nativeWidth);
        }
    }
    frame.size = CGSizeMake(targetWidth, targetHeight);
    frame.origin = CGPointMake(0, 0);
    return frame;
}

#pragma mark - UIScrollViewDelegate

- (UIView *)viewForZoomingInScrollView:(UIScrollView *)scrollView {
    return self.imageView;
}

- (void)scrollViewDidZoom:(UIScrollView *)scrollView {
    
    [scrollView setContentInset:[self contentInsetForScrollView:scrollView.zoomScale]];
    
    if (self.scrollView.scrollEnabled == NO) {
        self.scrollView.scrollEnabled = YES;
    }
    
    if (_flags.isAnimatingAPresentationOrDismissal == NO && _flags.isManuallyResizingTheScrollViewFrame == NO) {
        [self updateDimmingViewForCurrentZoomScale:YES];
    }
}

- (void)scrollViewDidEndZooming:(UIScrollView *)scrollView withView:(UIView *)view atScale:(CGFloat)scale {
    
    self.scrollView.scrollEnabled = (scale > 1);
    self.scrollView.contentInset = [self contentInsetForScrollView:scale];
}

- (void)scrollViewDidEndDragging:(UIScrollView *)scrollView willDecelerate:(BOOL)decelerate {
    
    CGPoint velocity = [scrollView.panGestureRecognizer velocityInView:scrollView.panGestureRecognizer.view];
    if (scrollView.zoomScale == 1 && (fabsf(velocity.x) > 1600 || fabsf(velocity.y) > 1600 ) ) {
        [self dismiss:YES];
    }
}

#pragma mark - Update Dimming View for Zoom Scale

- (void)updateDimmingViewForCurrentZoomScale:(BOOL)animated {
    CGFloat targetAlpha = (self.scrollView.zoomScale > 1) ? 1.0f : self.alphaForBackgroundDimmingOverlay;
    CGFloat duration = (animated) ? 0.35 : 0;
    [UIView animateWithDuration:duration delay:0 options:UIViewAnimationOptionCurveLinear | UIViewAnimationOptionBeginFromCurrentState animations:^{
        [self.blackBackdrop setAlpha:targetAlpha];
    } completion:nil];
}

#pragma mark - Gesture Recognizer Actions

- (void)imageDoubleTapped:(UITapGestureRecognizer *)sender {
    
    if (_flags.scrollViewIsAnimatingAZoom) {
        return;
    }
    
    CGPoint rawLocation = [sender locationInView:sender.view];
    CGPoint point = [self.scrollView convertPoint:rawLocation fromView:sender.view];
    CGRect targetZoomRect;
    UIEdgeInsets targetInsets;
    if (self.scrollView.zoomScale == 1.0f) {
        CGFloat zoomWidth = self.view.bounds.size.width / JTSImageViewController_TargetZoomForDoubleTap;
        CGFloat zoomHeight = self.view.bounds.size.height / JTSImageViewController_TargetZoomForDoubleTap;
        targetZoomRect = CGRectMake(point.x - (zoomWidth/2.0f), point.y - (zoomHeight/2.0f), zoomWidth, zoomHeight);
        targetInsets = [self contentInsetForScrollView:JTSImageViewController_TargetZoomForDoubleTap];
    } else {
        CGFloat zoomWidth = self.view.bounds.size.width * self.scrollView.zoomScale;
        CGFloat zoomHeight = self.view.bounds.size.height * self.scrollView.zoomScale;
        targetZoomRect = CGRectMake(point.x - (zoomWidth/2.0f), point.y - (zoomHeight/2.0f), zoomWidth, zoomHeight);
        targetInsets = [self contentInsetForScrollView:1.0f];
    }
    [self.view setUserInteractionEnabled:NO];
    _flags.scrollViewIsAnimatingAZoom = YES;
    [self.scrollView zoomToRect:targetZoomRect animated:YES];
    [self.scrollView setContentInset:targetInsets];
    __weak JTSImageViewController *weakSelf = self;
    dispatch_after(dispatch_time(DISPATCH_TIME_NOW, 0.35 * NSEC_PER_SEC), dispatch_get_main_queue(), ^{
        [weakSelf.view setUserInteractionEnabled:YES];
        _flags.scrollViewIsAnimatingAZoom = NO;
    });
}

- (void)imageSingleTapped:(id)sender {
    if (_flags.scrollViewIsAnimatingAZoom) {
        return;
    }
    [self dismiss:YES];
}

#pragma mark - Gesture Recognizer Delegate

- (BOOL)gestureRecognizer:(UIGestureRecognizer *)gestureRecognizer shouldReceiveTouch:(UITouch *)touch {
    
    BOOL shouldReceiveTouch = YES;
    
    if (shouldReceiveTouch && gestureRecognizer == self.panRecognizer) {
        shouldReceiveTouch = (self.scrollView.zoomScale == 1 && _flags.scrollViewIsAnimatingAZoom == NO);
    }
    return shouldReceiveTouch;
}

@end



