//
//  RequestCodeViewController.m
//  librecon
//
//  Created by Sergio Garcia on 08/10/14.
//  Copyright (c) 2014 Sergio Garcia. All rights reserved.
//

#import "RequestCodeViewController.h"
#import "UserDefaultsHelper.h"
#import "UIColor+Librecon.h"
#import "AppDelegate.h"
#import "SVProgressHUD.h"
#import "API.h"

@interface RequestCodeViewController () {
    
    NSString *appLanguaje;
}

@end

@implementation RequestCodeViewController

- (void)viewDidLoad {
    
    [super viewDidLoad];
    appLanguaje = [UserDefaultsHelper getActualLanguage];
    if (_fromMenu) {
        [self menuSetup];
    }
    [self languajeSetup];
    [self viewSetup];
}

- (void)menuSetup {
    
    UIBarButtonItem *menuItem = [[UIBarButtonItem alloc] init];
    [menuItem setImage:[UIImage imageNamed:@"reveal-icon"]];
    self.navigationItem.leftBarButtonItem = menuItem;
    SWRevealViewController *revealViewController = self.revealViewController;
    
    if (menuItem) {
        [menuItem setTarget: revealViewController];
        [menuItem setAction: @selector( revealToggle: )];
        [self.navigationController.navigationBar addGestureRecognizer:revealViewController.panGestureRecognizer];
    }
}

- (void)languajeSetup {
    
    [self setTitle:NSLocalizedString(@"AYUDA_CODIGO", nil)];
    [_lblHelp setText:NSLocalizedString(@"MENSAJE_AYUDA_USUARIO_INVITADO", nil)];
    [_lblCodeTitle setText:NSLocalizedString(@"TITULO_CODIGO", nil)];
}

- (void)viewSetup {
    
    [_btnCheckCode setBackgroundColor:[UIColor navigationBarBackgroundColor]];
    [_viewRequest setBackgroundColor:[UIColor navigationBarBackgroundColorWithAlpha:0.6f]];
    [_lblHelp setTextColor:[UIColor grayCustomColor]];
    _txtCodeInput.tag = 0;
    _txtCodeInput.delegate = self;
    
    [_txtCodeInput addTarget:self
                      action:@selector(textFieldDidChange:)
            forControlEvents:UIControlEventEditingChanged];
}


- (void)enableUI:(BOOL)value {
    
    [_btnCheckCode setEnabled:value];
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
        API *a =[API sharedClient];
        [a sendUUID:[UserDefaultsHelper getUUID]];
        [API setCustomHeader];
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
