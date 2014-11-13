//
//  GetCodeViewController.h
//  librecon
//
//  Created by Sergio Garcia on 15/10/14.
//  Copyright (c) 2014 Sergio Garcia. All rights reserved.
//

#import <UIKit/UIKit.h>

@interface GetCodeViewController : UIViewController <UITextFieldDelegate>

@property (weak, nonatomic) IBOutlet UIImageView *imgBackground;
@property (weak, nonatomic) IBOutlet NSLayoutConstraint *imgBackgroundTopConstraint;
@property (weak, nonatomic) IBOutlet NSLayoutConstraint *imgBackgroundLeftConstraint;
@property (weak, nonatomic) IBOutlet NSLayoutConstraint *imgBackgroundRightConstraint;
@property (weak, nonatomic) IBOutlet NSLayoutConstraint *imgBackgroundBottomConstraint;

@property (weak, nonatomic) IBOutlet UILabel *lblHelp;
@property (weak, nonatomic) IBOutlet UIButton *btnGetCode;
@property (weak, nonatomic) IBOutlet UIButton *btnCancel;

@property (weak, nonatomic) IBOutlet UITextField *txtEmailInput;
@property (weak, nonatomic) IBOutlet UILabel *lblEmailTitle;

- (IBAction)btnGetCodeAction:(id)sender;
- (IBAction)btnCancelAction:(id)sender;
@end
