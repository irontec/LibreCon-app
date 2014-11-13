//
//  GetCodeViewController.m
//  librecon
//
//  Created by Sergio Garcia on 15/10/14.
//  Copyright (c) 2014 Sergio Garcia. All rights reserved.
//

#import "GetCodeViewController.h"
#import "UIColor+Librecon.h"
#import "API.h"
#import "SVProgressHUD.h"

@interface GetCodeViewController ()

@end

@implementation GetCodeViewController

- (void)viewDidLoad {
    
    [super viewDidLoad];
    [self setupLanguage];
    [self setupView];
}

- (void)viewDidAppear:(BOOL)animated {
    
    [[NSNotificationCenter defaultCenter] removeObserver:self
                                                    name:@"popToRootOnSuccesRequest"
                                                  object:nil];
    [[NSNotificationCenter defaultCenter] addObserverForName:@"popToRootOnSuccesRequest"
                                                      object:nil
                                                       queue:nil
                                                  usingBlock:^(NSNotification *note) {
                                                      [self.navigationController popToRootViewControllerAnimated:YES];
                                                  }];
}

- (void)viewWillDisappear:(BOOL)animated {
    [[NSNotificationCenter defaultCenter] removeObserver:self
                                                    name:@"popToRootOnSuccesRequest"
                                                  object:nil];
}

- (void)setupLanguage {
    
    [_btnCancel setTitle:NSLocalizedString(@"CANCELAR", nil)
                forState:UIControlStateNormal];
    [_btnGetCode setTitle:NSLocalizedString(@"RECUPERAR_CODIGO", nil)
                 forState:UIControlStateNormal];
    [_lblHelp setText:NSLocalizedString(@"MENSAJE_AYUDA_SOLICITAR_CODIGO", nil)];
    [_lblEmailTitle setText:NSLocalizedString(@"EMAIL", nil)];
}

- (void)setupView {
    
    [_imgBackground setImage:[UIImage imageNamed:@"audience_blur.png"]];
    [_btnGetCode setBackgroundColor:[UIColor navigationBarBackgroundColor]];
    
    [self addParallaxEffect];
    
    _txtEmailInput.tag = 0;
    _txtEmailInput.delegate = self;
    
    [_txtEmailInput addTarget:self
                       action:@selector(textFieldDidChange:)
             forControlEvents:UIControlEventEditingChanged];
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

#pragma markf - UITextFieldDelegate

- (void)textFieldDidChange:(UITextField *)textField {
    
    CGFloat alphaValue = 0.30;
    NSInteger disableDigs = 19;
    if (textField.text.length > 0) {
        if (textField.text.length >= disableDigs) {
            if (_lblEmailTitle.alpha != 0) {
                [UIView animateWithDuration:0.1 animations:^{
                    [_lblEmailTitle setAlpha:0];
                }];
            }
        } else {
            if (_lblEmailTitle.alpha != alphaValue) {
                [UIView animateWithDuration:0.1 animations:^{
                    [_lblEmailTitle setAlpha:alphaValue];
                }];
            }
        }
    } else {
        if (_lblEmailTitle.alpha != 1.0) {
            [UIView animateWithDuration:0.1 animations:^{
                [_lblEmailTitle setAlpha:1.0];
            }];
        }
    }
}

- (BOOL)textFieldShouldReturn:(UITextField *)textField {
    
    [textField resignFirstResponder];
    return YES;
}

- (void)requestCodeByEmail:(NSString *)email {
    
    API *_api = [API sharedClient];
    [SVProgressHUD showWithStatus:NSLocalizedString(@"SOLICITAR_CODIGO", nil)];
    [_api requestCodeWithEmail:email withOnSuccessHandler:^(NSDictionary *content) {
        [SVProgressHUD showSuccessWithStatus:NSLocalizedString(@"SOLICITAR_CODIGO_OK", nil)];
        [[NSNotificationCenter defaultCenter] postNotificationName:@"popToRootOnSuccesRequest" object:nil];
    } andFailureHandler:^(AFHTTPRequestOperation *operation, NSError *error) {
        [SVProgressHUD showErrorWithStatus:NSLocalizedString(@"SOLICITAR_CODIGO_KO", nil)];
    }];
    [self.navigationController popToRootViewControllerAnimated:YES];
}

- (IBAction)btnGetCodeAction:(id)sender {
    
    if (_txtEmailInput.text.length <= 0) {
        return;
    }
    [self requestCodeByEmail:_txtEmailInput.text];
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
