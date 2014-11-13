//
//  ViewController.m
//  librecon
//
//  Created by Sergio Garcia on 15/09/14.
//  Copyright (c) 2014 Sergio Garcia. All rights reserved.
//

#import "MenuViewController.h"
#import "SWRevealViewController.h"
#import "UserProfileTableViewCell.h"
#import "MenuTableViewCell.h"
#import "UserDefaultsHelper.h"
#import "User.h"
#import "UIColor+Librecon.h"
#import "UIImageView+AFNetworking.h"
#import "API.h"
#import "RequestCodeViewController.h"
#import "Meeting.h"
#import "SVProgressHUD.h"
#import "AppDelegate.h"
#import "MeetingMapper.h"
#import "MeetingViewController.h"

@implementation MenuViewController {
    
    NSString *meetingIdFromPush;
}

- (void)viewDidLoad {
    
    [[NSNotificationCenter defaultCenter] removeObserver:self
                                                    name:NOTIFICATION_MEETING_FROM_PUSH
                                                  object:nil];
    [[NSNotificationCenter defaultCenter] addObserver:self
                                             selector:@selector(openMeetingFromNotification:)
                                                 name:NOTIFICATION_MEETING_FROM_PUSH
                                               object:nil];
    
    _tableView.dataSource = self;
    _tableView.delegate = self;
    [_tableView setTableFooterView:[[UIView alloc] initWithFrame:CGRectZero]];
}

- (void)viewWillAppear:(BOOL)animated {
    
    [_tableView reloadData];
    [self.revealViewController.frontViewController.view setUserInteractionEnabled:NO];
    [self.revealViewController.view addGestureRecognizer:self.revealViewController.panGestureRecognizer];
}

- (void)viewWillDisappear:(BOOL)animated {
    
    [self.revealViewController.frontViewController.view setUserInteractionEnabled:YES];
}

- (void)openMeetingFromNotification:(NSNotification *)notif {
    
    NSString *meetingId =notif.userInfo[@"meetingId"];
    [self openMeeting:meetingId];
}

- (void)btnLogoutAction:(id)sender {
    
    UIAlertView *alert = [[UIAlertView alloc] initWithTitle:NSLocalizedString(@"CERRAR_SESION", nil)
                                                    message:nil
                                                   delegate:self
                                          cancelButtonTitle:NSLocalizedString(@"NO", nil)
                                          otherButtonTitles:NSLocalizedString(@"SI", nil), nil];
    [alert show];
}

- (void)alertView:(UIAlertView *)alertView clickedButtonAtIndex:(NSInteger)buttonIndex {
    
    if (buttonIndex == 1) {
        API *_api = [API sharedClient];
        [_api forceUserLogin];
    }
}

#pragma mark - Table view data source

- (NSInteger)numberOfSectionsInTableView:(UITableView *)tableView {
    
    return 1;
}

- (NSInteger)tableView:(UITableView *)tableView numberOfRowsInSection:(NSInteger)section {
    
    return 8;
}

- (CGFloat)tableView:(UITableView *)tableView heightForRowAtIndexPath:(NSIndexPath *)indexPath {
    
    if (indexPath.row == 0) {
        return 180;
    } else {
        BOOL IS_IPHONE4 = (([[UIScreen mainScreen] bounds].size.height == 480) ? YES : NO);
        if (IS_IPHONE4) {
            return 42;
        } else {
            return 44;
        }
    }
}

- (UITableViewCell *)tableView:(UITableView *)tableView cellForRowAtIndexPath:(NSIndexPath *)indexPath {
    
    UITableViewCell *cell;
    if (indexPath.row == 0) {
        cell = [tableView dequeueReusableCellWithIdentifier:@"userCell" forIndexPath:indexPath];
    } else {
        cell = [tableView dequeueReusableCellWithIdentifier:@"menuCell" forIndexPath:indexPath];
    }
    
    [self configureCell:cell atIndexPath:indexPath];
    return cell;
}

- (void)configureCell:(UITableViewCell *)cell atIndexPath:(NSIndexPath *)indexPath {
    
    if (indexPath.row == 0) {
        User *user = [UserDefaultsHelper getUserData];
        UserProfileTableViewCell *mCell = (UserProfileTableViewCell *)cell;
        [mCell.btnLogout addTarget:self
                            action:@selector(btnLogoutAction:)
                  forControlEvents:UIControlEventTouchUpInside];
        [mCell.imgBackgorund setImage:[UIImage imageNamed:@"slider_blur.png"]];
        if (![UserDefaultsHelper getAnonymous]) {
            
            [mCell.imgProfile setImageWithURL:[NSURL URLWithString:user.picUrlCircle]
                             placeholderImage:[UIImage imageNamed:@"placeholder_people.png"]];
            
            [mCell.lblName setText:[NSString stringWithFormat:@"%@ %@", user.name, user.lastName]];
            [mCell.lblEmail setText:user.email];
        } else {
            
            [mCell.imgProfile setImage:[UIImage imageNamed:@"placeholder_people.png"]];
            [mCell.lblName setText:NSLocalizedString(@"INVITADO", nil)];
            [mCell.lblEmail setText:@""];
            [mCell.lblEmail setHidden:YES];
        }
    } else {
        MenuTableViewCell *mCell = (MenuTableViewCell *)cell;
        switch (indexPath.row) {
            case 1:
                [mCell.lblTitle setText:NSLocalizedString(@"AGENDA", nil)];
                [mCell.imgIcon setImage:[UIImage imageNamed:@"menu_schedule"]];
                break;
            case 2:
                [mCell.lblTitle setText:NSLocalizedString(@"ASISTENTES", nil)];
                [mCell.imgIcon setImage:[UIImage imageNamed:@"menu_attendees"]];
                break;
            case 3:
                [mCell.lblTitle setText:NSLocalizedString(@"REUNIONES", nil)];
                [mCell.imgIcon setImage:[UIImage imageNamed:@"menu_meeting"]];
                break;
            case 4:
                [mCell.lblTitle setText:NSLocalizedString(@"UBICACIONES", nil)];
                [mCell.imgIcon setImage:[UIImage imageNamed:@"menu_location"]];
                break;
            case 5:
                [mCell.lblTitle setText:NSLocalizedString(@"PATROCINADORES", nil)];
                [mCell.imgIcon setImage:[UIImage imageNamed:@"menu_sponsors"]];
                break;
            case 6:
                [mCell.lblTitle setText:NSLocalizedString(@"PHOTOCALL", nil)];
                [mCell.imgIcon setImage:[UIImage imageNamed:@"menu_photocall"]];
                break;
            case 7:
                [mCell.lblTitle setText:NSLocalizedString(@"ACERCADE", nil)];
                [mCell.imgIcon setImage:[UIImage imageNamed:@"menu_about"]];
                break;
        }
    }
}

- (void)tableView:(UITableView *)tableView didSelectRowAtIndexPath:(NSIndexPath *)indexPath {
    
    switch (indexPath.row) {
        case 1:
            [self performSegueWithIdentifier:@"openSchedule" sender:self];
            break;
        case 2:
            [self openAssistant];
            break;
        case 3:
            [self openMeeting:nil];
            break;
        case 4:
            [self performSegueWithIdentifier:@"openLocations" sender:self];
            break;
        case 5:
            [self performSegueWithIdentifier:@"openSponsors" sender:self];
            break;
        case 6:
            [self performSegueWithIdentifier:@"openPhotoCall" sender:self];
            break;
        case 7:
            [self performSegueWithIdentifier:@"openAbout" sender:self];
            break;
    }
    [_tableView deselectRowAtIndexPath:indexPath animated:YES];
}

- (void)openAssistant {
    
    if (![UserDefaultsHelper getAnonymous]) {
        [self performSegueWithIdentifier:@"openAssistants" sender:self];
    } else {
        [self openRequestCodeController];
    }
}

- (void)openMeeting:(NSString *)meetingId {
    
    if (![UserDefaultsHelper getAnonymous]) {
        [self performSegueWithIdentifier:@"openMeetings" sender:meetingId];
    } else {
        [self openRequestCodeController];
    }
}

- (void)openRequestCodeController {
    
    [self performSegueWithIdentifier:@"requestCode" sender:self];
}

#pragma mark - Navigation

- (void)prepareForSegue:(UIStoryboardSegue *)segue sender:(id)sender {
    
    if ([[segue identifier] isEqualToString:@"requestCode"]) {
        UINavigationController *navController = [segue destinationViewController];
        RequestCodeViewController *rCode = (RequestCodeViewController *)([navController viewControllers][0]);
        rCode.fromMenu = YES;
    } else if ([[segue identifier] isEqualToString:@"openMeetings"]) {
        NSString *meetingId = (NSString *)sender;
        if (meetingId) {
            UINavigationController *navController = [segue destinationViewController];
            MeetingViewController *meetingVC = (MeetingViewController *)([navController viewControllers][0]);
            meetingVC.meetingId = meetingId;
        }
    }
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
