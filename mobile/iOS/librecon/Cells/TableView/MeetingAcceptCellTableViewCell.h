//
//  MeetingAcceptCellTableViewCell.h
//  librecon
//
//  Created by Sergio Garcia on 03/10/14.
//  Copyright (c) 2014 Sergio Garcia. All rights reserved.
//

#import <UIKit/UIKit.h>

//height 115
@interface MeetingAcceptCellTableViewCell : UITableViewCell

@property (weak, nonatomic) IBOutlet UIImageView *imgProfile;
@property (weak, nonatomic) IBOutlet UILabel *lblOrigin;
@property (weak, nonatomic) IBOutlet NSLayoutConstraint *lblOriginHeightConstraint;
@property (weak, nonatomic) IBOutlet UILabel *lblDest;
@property (weak, nonatomic) IBOutlet NSLayoutConstraint *lblDestHeightConstraint;
@property (weak, nonatomic) IBOutlet UILabel *lblState;
@property (weak, nonatomic) IBOutlet NSLayoutConstraint *lblStateHeightConstraint;
@property (weak, nonatomic) IBOutlet UILabel *lblTime;
@property (weak, nonatomic) IBOutlet NSLayoutConstraint *lblTimeHeightConstraint;
@property (weak, nonatomic) IBOutlet UILabel *lblMoment;
@property (weak, nonatomic) IBOutlet NSLayoutConstraint *lblMomentHeightConstraint;
@property (weak, nonatomic) IBOutlet UILabel *lbllocation;
@property (weak, nonatomic) IBOutlet NSLayoutConstraint *lblLocationHeightConstraint;

@end
