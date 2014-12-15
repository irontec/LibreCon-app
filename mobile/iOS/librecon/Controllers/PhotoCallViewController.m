//
//  PhotoCallViewController.m
//  librecon
//
//  Created by Sergio Garcia on 08/10/14.
//  Copyright (c) 2014 Sergio Garcia. All rights reserved.
//

#import "PhotoCallViewController.h"
#import "UserDefaultsHelper.h"
#import "API.h"
#import "PhotoCollectionViewCell.h"
#import "Photo.h"
#import "UIImageView+AFNetworking.h"
#import "UIColor+Librecon.h"
#import "UIImage+AverageColor.h"

#import "JTSImageViewController.h"
#import "JTSImageInfo.h"

#define CELLS_MARGIN 2
#define COLUMNS 3

@interface PhotoCallViewController () {
    
    UIScrollView *mScroll;
    UIImageView *fullImage;
}

@end

@implementation PhotoCallViewController {
    
    NSArray *photos;
    
    NSString *appLanguaje;
    
    BOOL isLoading;
    UIRefreshControl *refreshControl;
}

- (void)viewDidLoad {
    
    [super viewDidLoad];
    appLanguaje = [UserDefaultsHelper getActualLanguage];
    [self menuSetup];
    [self languajeSetup];
    [self viewSetup];
}

- (void)menuSetup {
    
    SWRevealViewController *revealViewController = self.revealViewController;
    if (revealViewController) {
        [self.revealButtonItem setTarget: revealViewController];
        [self.revealButtonItem setAction: @selector( revealToggle: )];
        [self.navigationController.navigationBar addGestureRecognizer:revealViewController.panGestureRecognizer];
    }
}

- (void)languajeSetup {
    
    [self setTitle:NSLocalizedString(@"PHOTOCALL", nil)];
}

- (void)viewSetup {
    
    isLoading = NO;
    [self setBackgroundEmptyView];
    [self reloadModeSetup];
    
    _collectionView.dataSource = self;
    _collectionView.delegate = self;
    
    _collectionView.contentOffset = CGPointMake(0, - refreshControl.frame.size.height);
    [refreshControl beginRefreshing];
    
    [self loadData];
    
}

#pragma mark - Reload Setup

- (void)reloadModeSetup {
    
    refreshControl = [[UIRefreshControl alloc] init];
    [refreshControl setBackgroundColor:[UIColor whiteColor]];
    
    [refreshControl addTarget:self action:@selector(refresh:) forControlEvents:UIControlEventValueChanged];
    NSMutableAttributedString *aString = [[NSMutableAttributedString alloc] initWithString:NSLocalizedString(@"CARGANDO", nil)];
    [aString addAttribute:NSForegroundColorAttributeName value:[UIColor grayCustomColor] range:NSMakeRange(0,aString.length)];
    [refreshControl setAttributedTitle:aString];
    
    [_collectionView addSubview:refreshControl];
}

- (void)refresh:(UIRefreshControl *)refreshControl {
    
    if (isLoading) {
        return;
    }
    isLoading = YES;
    [self loadData];
}

- (void)setBackgroundEmptyView {
    
    // Background View
    UILabel *label = [[UILabel alloc] initWithFrame:_collectionView.frame];
    [label setNumberOfLines:4];
    [label setText:NSLocalizedString(@"NO_DATA_PHOTOCALL", nil)];
    [label setTextAlignment:NSTextAlignmentCenter];
    [label setTextColor:[UIColor tableViewBackgroundTextColor]];
    [label sizeToFit];
    [_collectionView setBackgroundView:label];
}

- (void)loadData {
    
    API *_api = [API sharedClient];
    [_api getPhotosWithOnSuccessHandler:^(NSDictionary *content) {
        NSArray *data = [NSArray arrayWithArray:content[@"data"][@"photos"]];
        NSMutableArray *tempPhotos = [[NSMutableArray alloc] init];
        for (NSDictionary *photo in data) {
            Photo *p = [[Photo alloc] init];
            [p initWithDictionary:photo];
            [tempPhotos addObject:p];
        }
        photos = tempPhotos;
        [refreshControl endRefreshing];
        [_collectionView reloadData];
        isLoading = NO;
    } andFailureHandler:^(AFHTTPRequestOperation *operation, NSError *error) {
        isLoading = NO;
        photos = nil;
        [refreshControl endRefreshing];
        [_collectionView reloadData];
    }];
}

#pragma mark - UICollectionViewDataSource

- (NSInteger)collectionView:(UICollectionView *)collectionView numberOfItemsInSection:(NSInteger)section {

    NSInteger count = (photos ? [photos count] : 0);
    if (count == 0) {
        [_collectionView.backgroundView setHidden:NO];
    } else {
        [_collectionView.backgroundView setHidden:YES];
    }
    return count;
}

- (UICollectionViewCell *)collectionView:(UICollectionView *)collectionView cellForItemAtIndexPath:(NSIndexPath *)indexPath {
    
    Photo *p = [photos objectAtIndex:indexPath.row];
    PhotoCollectionViewCell *cell = [collectionView dequeueReusableCellWithReuseIdentifier:@"photoCollectionViewCell" forIndexPath:indexPath];
    [cell.imgPhoto setImageWithURL:[NSURL URLWithString:p.thumbnailUrl] placeholderImage:[UIImage imageNamed:@"placeholder_librecon.png"]];
    return cell;
}

#pragma mark - UICollectionViewDelegate

- (void)collectionView:(UICollectionView *)collectionView didSelectItemAtIndexPath:(NSIndexPath *)indexPath {
    
    [collectionView deselectItemAtIndexPath:indexPath animated:YES];
    [self openImageWithIndexPath:indexPath];
}

#pragma mark – UICollectionView DelegateFlowLayout

- (CGSize)collectionView:(UICollectionView *)collectionView layout:(UICollectionViewLayout*)collectionViewLayout sizeForItemAtIndexPath:(NSIndexPath *)indexPath {
    
    CGFloat height, width;
    
    width = (_collectionView.frame.size.width / COLUMNS) - (CELLS_MARGIN * 2);
    height = width;
    return  CGSizeMake(width, height);
}

- (CGFloat)collectionView:(UICollectionView *)collectionView layout:(UICollectionViewLayout*)collectionViewLayout minimumInteritemSpacingForSectionAtIndex:(NSInteger)section {
    
    return CELLS_MARGIN;//separation between columns
}

- (CGFloat)collectionView:(UICollectionView *)collectionView layout:(UICollectionViewLayout*)collectionViewLayout minimumLineSpacingForSectionAtIndex:(NSInteger)section {
    
    return CELLS_MARGIN;//separation between cells
}

- (UIEdgeInsets)collectionView:(UICollectionView *)collectionView layout:(UICollectionViewLayout*)collectionViewLayout insetForSectionAtIndex:(NSInteger)section {
    
    return UIEdgeInsetsMake(CELLS_MARGIN, CELLS_MARGIN, CELLS_MARGIN, CELLS_MARGIN);//CollectionView borders top first and last cell
}

#pragma mark - Photo Call

- (void)openImageWithIndexPath:(NSIndexPath *)indexPath {
    
    Photo *p = [photos objectAtIndex:indexPath.row];
    PhotoCollectionViewCell *cell = (PhotoCollectionViewCell *)[_collectionView cellForItemAtIndexPath:indexPath];

    // Create image info
    JTSImageInfo *imageInfo = [[JTSImageInfo alloc] init];
    imageInfo.image = nil;
    imageInfo.placeholderImage = cell.imgPhoto.image;
    imageInfo.imageURL = [NSURL URLWithString:p.url];
    imageInfo.referenceRect = cell.frame;
    imageInfo.referenceView = cell.superview;
    imageInfo.contentMode = UIViewContentModeScaleAspectFit;
    // Setup view controller
    JTSImageViewController *imageViewer = [[JTSImageViewController alloc] initWithImageInfo:imageInfo
                                                                                       mode:JTSImageViewControllerMode_Image
                                                                            backgroundStyle:JTSImageViewControllerBackgroundStyle_ScaledDimmed];
    [imageViewer showFromViewController:self
                             transition:JTSImageViewControllerTransition_FromOriginalPosition];
}

#pragma mark - Rotation

- (BOOL)shouldAutorotateToInterfaceOrientation:(UIInterfaceOrientation)toInterfaceOrientation
{
    return (toInterfaceOrientation == UIInterfaceOrientationPortrait);
}

- (BOOL)shouldAutorotate
{
    return NO;
}

- (NSUInteger)supportedInterfaceOrientations
{
    return UIInterfaceOrientationMaskAll;
}

- (UIInterfaceOrientation)preferredInterfaceOrientationForPresentation
{
    return UIInterfaceOrientationPortrait;
}

@end
