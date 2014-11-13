//
//  AboutViewController.m
//  librecon
//
//  Created by Sergio Garcia on 16/10/14.
//  Copyright (c) 2014 Sergio Garcia. All rights reserved.
//

#import "AboutViewController.h"
#import "UserDefaultsHelper.h"
#import "SVProgressHUD.h"

@interface AboutViewController ()

@end

@implementation AboutViewController

- (void)viewDidLoad {
    
    [super viewDidLoad];
    [self setTitle:NSLocalizedString(@"ACERCADE", nil)];
    [self menuSetup];
    
    _webView.scrollView.bounces = NO;
    _webView.multipleTouchEnabled = NO;
    _webView.backgroundColor = [UIColor clearColor];
    _webView.opaque = NO;
    
    _webView.delegate = self;
    NSString *appLanguaje = [UserDefaultsHelper getActualLanguage];
    [_webView loadRequest:[NSURLRequest requestWithURL:[NSURL URLWithString:[kAppAboutURL stringByAppendingString:appLanguaje]]]];
}

- (void)viewWillDisappear:(BOOL)animated {
    
    [_webView stopLoading];
    [SVProgressHUD dismiss];
}

- (void)menuSetup {
    
    SWRevealViewController *revealViewController = self.revealViewController;
    if (revealViewController) {
        [self.revealButtonItem setTarget: revealViewController];
        [self.revealButtonItem setAction: @selector( revealToggle: )];
        [self.navigationController.navigationBar addGestureRecognizer:revealViewController.panGestureRecognizer];
    }
}

#pragma mark - UIWebViewDelegate

- (BOOL)webView:(UIWebView *)webView shouldStartLoadWithRequest:(NSURLRequest *)request navigationType:(UIWebViewNavigationType)navigationType {
    
    NSLog(@"shouldStartLoadWithRequest: %@", request.URL);
    if ([[request.URL absoluteString] rangeOfString:kAppAboutURL].location != NSNotFound) {
        NSLog(@"Authorized");
        [SVProgressHUD showWithStatus:NSLocalizedString(@"CARGANDO", nil)];
        return YES;
    } else {
        NSLog(@"Blocked");
        return NO;
    }
}

- (void)webViewDidStartLoad:(UIWebView *)webView {
    
    NSLog(@"webViewDidStartLoad");
}

- (void)webViewDidFinishLoad:(UIWebView *)webView {
    
    [SVProgressHUD dismiss];
    NSLog(@"webViewDidFinishLoad");
}

- (void)webView:(UIWebView *)webView didFailLoadWithError:(NSError *)error {
    
    [SVProgressHUD showErrorWithStatus:@" "];
    NSLog(@"didFailLoadWithError: %@", error);
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
