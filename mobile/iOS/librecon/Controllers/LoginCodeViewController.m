//
//  LoginCodeViewController.m
//  librecon
//
//  Created by Sergio Garcia on 08/10/14.
//  Copyright (c) 2014 Sergio Garcia. All rights reserved.
//

#import "LoginCodeViewController.h"
#import "AppDelegate.h"
#import "SVProgressHUD.h"
#import "API.h"
#import "User.h"
#import "UserDefaultsHelper.h"
#import "UIColor+Librecon.h"

@interface LoginCodeViewController ()

@end

@implementation LoginCodeViewController

- (void)viewDidLoad {
    
    [super viewDidLoad];
    [self setupLanguage];
    [self setupView];
}

- (void)setupLanguage {
    
    [_btnCheckCode setTitle:NSLocalizedString(@"COMPROBAR_CODIGO", nil)
                   forState:UIControlStateNormal];
    [_btnCancel setTitle:NSLocalizedString(@"CANCELAR", nil)
                forState:UIControlStateNormal];
    [_btnGetCode setTitle:NSLocalizedString(@"RECUPERAR_CODIGO", nil)
                 forState:UIControlStateNormal];
    [_lblHelp setText:NSLocalizedString(@"MENSAJE_AYUDA_USUARIO", nil)];
    [_lblCodeTitle setText:NSLocalizedString(@"TITULO_CODIGO", nil)];
}

- (void)setupView {
    
    [_imgBackground setImage:[UIImage imageNamed:@"audience_blur.png"]];
    [_btnCheckCode setBackgroundColor:[UIColor navigationBarBackgroundColor]];
    
    [self addParallaxEffect];
    
    _txtCodeInput.tag = 0;
    _txtCodeInput.delegate = self;
    
    [_txtCodeInput addTarget:self
                      action:@selector(textFieldDidChange:)
            forControlEvents:UIControlEventEditingChanged];
}

- (void)enableUI:(BOOL)value {
    
    [_btnCheckCode setEnabled:value];
    [_btnCancel setEnabled:value];
}

- (void)addParallaxEffect {
    
    // Update image constraints with value
    _imgBackgroundLeftConstraint.constant = _imgBackgroundLeftConstraint.constant - parallaxValue;
    _imgBackgroundTopConstraint.constant = _imgBackgroundTopConstraint.constant - parallaxValue;
    _imgBackgroundRightConstraint.constant = _imgBackgroundRightConstraint.constant - parallaxValue;
    _imgBackgroundBottomConstraint.constant = _imgBackgroundBottomConstraint.constant - parallaxValue;
    
    [self.view layoutIfNeeded];
    
    // Set vertical effect
    UIInterpolatingMotionEffect *verticalMotionEffect = [[UIInterpolatingMotionEffect alloc] initWithKeyPath:@"center.y"
                                                                                                        type:UIInterpolatingMotionEffectTypeTiltAlongVerticalAxis];
    verticalMotionEffect.minimumRelativeValue = @(-parallaxValue);
    verticalMotionEffect.maximumRelativeValue = @(parallaxValue);
    
    // Set horizontal effect
    UIInterpolatingMotionEffect *horizontalMotionEffect = [[UIInterpolatingMotionEffect alloc] initWithKeyPath:@"center.x"
                                                                                                          type:UIInterpolatingMotionEffectTypeTiltAlongHorizontalAxis];
    horizontalMotionEffect.minimumRelativeValue = @(-parallaxValue);
    horizontalMotionEffect.maximumRelativeValue = @(parallaxValue);
    
    // Create group to combine both
    UIMotionEffectGroup *group = [UIMotionEffectGroup new];
    group.motionEffects = @[horizontalMotionEffect, verticalMotionEffect];
    
    // Add both effects to your view
    [_imgBackground addMotionEffect:group];
}

- (void)openMainController {
    
    AppDelegate *app = (AppDelegate *)[[UIApplication sharedApplication] delegate];
    [app loadMainController];
}

#pragma markf - UITextFieldDelegate

- (void)textFieldDidChange:(UITextField *)textField {
    
    CGFloat alphaValue = 0.30;
    NSInteger disableDigs = 10;
    if (textField.text.length > 0) {
        if (textField.text.length >= disableDigs) {
            if (_lblCodeTitle.alpha != 0) {
                [UIView animateWithDuration:0.1 animations:^{
                    [_lblCodeTitle setAlpha:0];
                }];
            }
        } else {
            if (_lblCodeTitle.alpha != alphaValue) {
                [UIView animateWithDuration:0.1 animations:^{
                    [_lblCodeTitle setAlpha:alphaValue];
                }];
            }
        }
    } else {
        if (_lblCodeTitle.alpha != 1.0) {
            [UIView animateWithDuration:0.1 animations:^{
                [_lblCodeTitle setAlpha:1.0];
            }];
        }
    }
}

- (BOOL)textFieldShouldReturn:(UITextField *)textField {
    
    [textField resignFirstResponder];
    return YES;
}

#pragma mark - API Login

- (void)performLoginWithCode:(NSString *)code {
    
    [self enableUI:NO];
    [SVProgressHUD showWithStatus:NSLocalizedString(@"LOGIN", nil)];
    API *_api = [API sharedClient];
    [_api athenticateWithCode:code withOnSuccessHandler:^(NSDictionary *content) {
        User *user = [[User alloc] init];
        [user initWithDictionary:content[@"data"][@"assistant"]];
        [UserDefaultsHelper setUserHash:user.hashUser];
        [UserDefaultsHelper setUserData:user];
        [API setCustomHeader];
        API *a =[API sharedClient];
        [a sendUUID:[UserDefaultsHelper getUUID]];
        [SVProgressHUD dismiss];
        AppDelegate *app = (AppDelegate *)[[UIApplication sharedApplication] delegate];
        [app checkDataState];
        [self enableUI:YES];
        [self openMainController];
    } andFailureHandler:^(AFHTTPRequestOperation *operation, NSError *error) {
        [SVProgressHUD showErrorWithStatus:NSLocalizedString(@"LOGIN_ERROR", nil)];
        [self enableUI:YES];
    }];
}

#pragma mark - Buttons Actions

- (IBAction)btnCheckCodeAction:(id)sender {
    
    if (_txtCodeInput.text.length <= 0) {
        return;
    }
    [self performLoginWithCode:_txtCodeInput.text];
}

- (IBAction)btnCancelAction:(id)sender {
    
    [self.navigationController popViewControllerAnimated:YES];
}

#pragma mark - Rotation

- (BOOL)shouldAutorotateToInterfaceOrientation:(UIInterfaceOrientation)toInterfaceOrientation
{
    return (toInterfaceOrientation == UIInterfaceOrientationPortrait);
}

- (BOOL)shouldAutorotate
{
    return YES;
}

- (NSUInteger)supportedInterfaceOrientations
{
    return UIInterfaceOrientationMaskPortrait;
}

- (UIInterfaceOrientation)preferredInterfaceOrientationForPresentation
{
    return UIInterfaceOrientationPortrait;
}

@end
