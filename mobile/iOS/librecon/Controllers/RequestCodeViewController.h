//
//  RequestCodeViewController.h
//  librecon
//
//  Created by Sergio Garcia on 08/10/14.
//  Copyright (c) 2014 Sergio Garcia. All rights reserved.
//

#import <UIKit/UIKit.h>
#import "SWRevealViewController.h"

@interface RequestCodeViewController : UIViewController <UITextFieldDelegate>

@property (nonatomic) BOOL fromMenu;

@property (weak, nonatomic) IBOutlet UILabel *lblHelp;
@property (weak, nonatomic) IBOutlet UILabel *lblCodeTitle;

@property (weak, nonatomic) IBOutlet UITextField *txtCodeInput;
@property (weak, nonatomic) IBOutlet UIButton *btnCheckCode;

@property (weak, nonatomic) IBOutlet UIView *viewRequest;

- (IBAction)btnCheckCodeAction:(id)sender;
@end
