//
//  JTSImageViewController.h
//
//
//  Created by Jared Sinclair on 3/28/14.
//  Copyright (c) 2014 Nice Boy LLC. All rights reserved.
//

@import UIKit;

#import "JTSImageInfo.h"

///--------------------------------------------------------------------------------------------------------------------
/// Definitions
///--------------------------------------------------------------------------------------------------------------------

typedef NS_ENUM(NSInteger, JTSImageViewControllerMode) {
    JTSImageViewControllerMode_Image
};

typedef NS_ENUM(NSInteger, JTSImageViewControllerTransition) {
    JTSImageViewControllerTransition_FromOriginalPosition
};

typedef NS_ENUM(NSInteger, JTSImageViewControllerBackgroundStyle) {
    JTSImageViewControllerBackgroundStyle_ScaledDimmed,
    JTSImageViewControllerBackgroundStyle_ScaledDimmedBlurred,
};

extern CGFloat const JTSImageViewController_DefaultAlphaForBackgroundDimmingOverlay;
extern CGFloat const JTSImageViewController_DefaultBackgroundBlurRadius;

///--------------------------------------------------------------------------------------------------------------------
/// JTSImageViewController
///--------------------------------------------------------------------------------------------------------------------

@interface JTSImageViewController : UIViewController

@property (strong, nonatomic, readonly) JTSImageInfo *imageInfo;

@property (strong, nonatomic, readonly) UIImage *image;

@property (assign, nonatomic, readonly) JTSImageViewControllerMode mode;

@property (assign, nonatomic, readonly) JTSImageViewControllerBackgroundStyle backgroundStyle;

/**
 Designated initializer.
 
 @param imageInfo The source info for image and transition metadata. Required.
 
 @param mode The mode to be used. (JTSImageViewController has an alternate alt text mode). Required.
 
 @param backgroundStyle Currently, either scaled-and-dimmed, or scaled-dimmed-and-blurred. 
 The latter is like Tweetbot 3.0's background style.
 */
- (instancetype)initWithImageInfo:(JTSImageInfo *)imageInfo
                             mode:(JTSImageViewControllerMode)mode
                  backgroundStyle:(JTSImageViewControllerBackgroundStyle)backgroundStyle;

/**
 JTSImageViewController is presented from viewController as a UIKit modal view controller.
 
 It's first presented as a full-screen modal *without* animation. At this stage the view controller
 is merely displaying a snapshot of viewController's topmost parentViewController's view.
 
 Next, there is an animated transition to a full-screen image viewer.
 */
- (void)showFromViewController:(UIViewController *)viewController
                    transition:(JTSImageViewControllerTransition)transition;

/**
 Dismisses the image viewer. Must not be called while previous presentation or dismissal is still in flight.
 */
- (void)dismiss:(BOOL)animated;

@end