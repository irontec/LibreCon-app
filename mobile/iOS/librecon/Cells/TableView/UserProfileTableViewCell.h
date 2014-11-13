//
//  UserProfileTableViewCell.h
//  librecon
//
//  Created by Sergio Garcia on 17/09/14.
//  Copyright (c) 2014 Sergio Garcia. All rights reserved.
//

#import <UIKit/UIKit.h>

@interface UserProfileTableViewCell : UITableViewCell
@property (weak, nonatomic) IBOutlet UIImageView *imgBackgorund;
@property (weak, nonatomic) IBOutlet UIImageView *imgProfile;
@property (weak, nonatomic) IBOutlet UILabel *lblName;
@property (weak, nonatomic) IBOutlet UILabel *lblEmail;

@property (weak, nonatomic) IBOutlet UIButton *btnLogout;
@property (weak, nonatomic) IBOutlet NSLayoutConstraint *logoutTopConstraint;

@end
