//
//  SponsorTableViewCell.h
//  librecon
//
//  Created by Sergio Garcia on 29/09/14.
//  Copyright (c) 2014 Sergio Garcia. All rights reserved.
//

#import <UIKit/UIKit.h>

@interface SponsorTableViewCell : UITableViewCell

@property (weak, nonatomic) IBOutlet UIImageView *imgSponsor;
@property (weak, nonatomic) IBOutlet UILabel *lblName;
@property (weak, nonatomic) IBOutlet UILabel *lblUrl;

@end
