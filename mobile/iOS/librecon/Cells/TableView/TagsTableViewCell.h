//
//  TagsTableViewCell.h
//  librecon
//
//  Created by Sergio Garcia on 06/10/14.
//  Copyright (c) 2014 Sergio Garcia. All rights reserved.
//

#import <UIKit/UIKit.h>

@interface TagsTableViewCell : UITableViewCell
@property (weak, nonatomic) IBOutlet UICollectionView *collectionView;
@property (weak, nonatomic) IBOutlet UILabel *lblTagsTitle;

@end
