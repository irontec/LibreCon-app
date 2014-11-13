//
//  LoginViewController.m
//  librecon
//
//  Created by Sergio Garcia on 15/09/14.
//  Copyright (c) 2014 Sergio Garcia. All rights reserved.
//

#import "LoginViewController.h"
#import "AppDelegate.h"
#import "SVProgressHUD.h"
#import "API.h"
#import "UserDefaultsHelper.h"
#import "UIColor+Librecon.h"

@interface LoginViewController () {
    
    UIImage *blurredImage;
}

@end

@implementation LoginViewController

- (void)viewDidLoad {
    
    [super viewDidLoad];
    [self setupLanguage];
    [self setupView];
}

- (void)setupLanguage {
    
    [_lblMainTitle setText:NSLocalizedString(@"BIENVENIDA", nil)];
    [_lblCodeTitle setText:NSLocalizedString(@"TITULO_CODIGO", nil)];
    [_btnCheckCode setTitle:NSLocalizedString(@"ENTRAR", nil)
                   forState:UIControlStateNormal];
    [_btnAnonymous setTitle:NSLocalizedString(@"INVITADO", nil)
                   forState:UIControlStateNormal];
}

- (void)setupView {
    
    [_imgBackground setImage:[UIImage imageNamed:@"audience_blur.png"]];
    [_btnCheckCode setBackgroundColor:[UIColor navigationBarBackgroundColor]];
    [_btnAnonymous setTitleColor:[UIColor colorWithWhite:1 alpha:0.8]
                        forState:UIControlStateNormal];
    
    [self addParallaxEffect];
}

- (void)addParallaxEffect {
    
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

#pragma mark - Buttons Actions

- (IBAction)btnAnonymousAction:(id)sender {
    
    [UserDefaultsHelper setAnonymous:YES];
    AppDelegate *app = (AppDelegate *)[[UIApplication sharedApplication] delegate];
    [app checkDataState];
    [app loadMainController];
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
