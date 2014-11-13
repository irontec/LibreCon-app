//
//  LinkTableViewCell.h
//  librecon
//
//  Created by Sergio Garcia on 06/10/14.
//  Copyright (c) 2014 Sergio Garcia. All rights reserved.
//

#import <UIKit/UIKit.h>

@interface LinkTableViewCell : UITableViewCell

@property (weak, nonatomic) IBOutlet UILabel *lblLink;
@property (weak, nonatomic) IBOutlet UIImageView *imgLink;

- (void)setLinkType:(NSString *)type;

@end
