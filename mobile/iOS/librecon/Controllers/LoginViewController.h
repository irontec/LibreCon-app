//
//  LoginViewController.h
//  librecon
//
//  Created by Sergio Garcia on 15/09/14.
//  Copyright (c) 2014 Sergio Garcia. All rights reserved.
//

#import <UIKit/UIKit.h>

@interface LoginViewController : UIViewController

@property (weak, nonatomic) IBOutlet UIImageView *imgBackground;

@property (weak, nonatomic) IBOutlet NSLayoutConstraint *imgBackgroundTopConstraint;
@property (weak, nonatomic) IBOutlet NSLayoutConstraint *imgBackgroundLeftConstraint;
@property (weak, nonatomic) IBOutlet NSLayoutConstraint *imgBackgroundRightConstraint;
@property (weak, nonatomic) IBOutlet NSLayoutConstraint *imgBackgroundBottomConstraint;

@property (weak, nonatomic) IBOutlet NSLayoutConstraint *contraintIconTitleSeparator;
@property (weak, nonatomic) IBOutlet NSLayoutConstraint *constrainTitleCodeSeparator;

@property (weak, nonatomic) IBOutlet UILabel *lblMainTitle;
@property (weak, nonatomic) IBOutlet UILabel *lblCodeTitle;

@property (weak, nonatomic) IBOutlet UIButton *btnCheckCode;
@property (weak, nonatomic) IBOutlet UIButton *btnAnonymous;

- (IBAction)btnAnonymousAction:(id)sender;
@end
