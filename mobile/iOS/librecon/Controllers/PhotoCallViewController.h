//
//  PhotoCallViewController.h
//  librecon
//
//  Created by Sergio Garcia on 08/10/14.
//  Copyright (c) 2014 Sergio Garcia. All rights reserved.
//

#import <UIKit/UIKit.h>
#import "ParentViewController.h"
#import "SWRevealViewController.h"

@interface PhotoCallViewController : ParentViewController <UICollectionViewDataSource, UICollectionViewDelegate>

@property (weak, nonatomic) IBOutlet UIBarButtonItem *revealButtonItem;
@property (weak, nonatomic) IBOutlet UICollectionView *collectionView;
@end
