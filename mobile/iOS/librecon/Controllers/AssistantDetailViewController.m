//
//  AssistantDetailViewController.m
//  librecon
//
//  Created by Sergio Garcia on 18/09/14.
//  Copyright (c) 2014 Sergio Garcia. All rights reserved.
//

#import "AssistantDetailViewController.h"
#import "API.h"
#import "SVProgressHUD.h"
#import "AssistantHeaderTableViewCell.h"
#import "AssistantInterestTitleTableViewCell.h"
#import "AssistantInterestItemTableViewCell.h"
#import "AssistantRequestMeetingTableViewCell.h"
#import "UIImageView+AFNetworking.h"
#import "UIColor+Librecon.h"
#import "AppDelegate.h"

@interface AssistantDetailViewController () {
    
    NSArray *interestArray;
}

@end

@implementation AssistantDetailViewController

- (void)viewDidLoad {
    
    [super viewDidLoad];
    [self languajeSetup];
    [self viewSetup];
}

- (void)viewWillAppear:(BOOL)animated {
    
    [[NSNotificationCenter defaultCenter] removeObserver:self
                                                    name:@"updateMeetingsFromApiInAssistantDetail"
                                                  object:nil];
    [[NSNotificationCenter defaultCenter] addObserverForName:@"updateMeetingsFromApiInAssistantDetail"
                                                      object:nil
                                                       queue:nil
                                                  usingBlock:^(NSNotification *note) {
                                                      AppDelegate *delegate = (AppDelegate *)[[UIApplication sharedApplication] delegate];
                                                      [delegate checkMeetings];
                                                      [self.navigationController popViewControllerAnimated:YES];
                                                  }];
}

- (void)vieDidDisappear:(BOOL)animated {
    
    [[NSNotificationCenter defaultCenter] removeObserver:self
                                                    name:@"updateMeetingsFromApiInAssistantDetail"
                                                  object:nil];
}

- (void)languajeSetup {
    
}

- (void)viewSetup {
    
    [self.navigationController.navigationBar setTranslucent:NO];
    [self.navigationController.navigationBar setBackgroundImage:[[UIImage alloc] init] forBarMetrics:UIBarMetricsDefault];
    [self.navigationController.navigationBar setShadowImage:[[UIImage alloc] init]];
    
    if (![_assistant.interests isEqualToString:@""]) {
        interestArray = [_assistant.interests componentsSeparatedByString:@","];
    }
    
    _tableView.dataSource = self;
    _tableView.delegate = self;
    
    UIView *footer = [[UIView alloc] initWithFrame:CGRectMake(0, 0, _tableView.frame.size.width, 5)];
    [footer setBackgroundColor:[UIColor clearColor]];
    [_tableView setTableFooterView:footer];
    
    [self setTitle:[NSString stringWithFormat:@"%@ %@", _assistant.name, _assistant.lastName]];
}

- (IBAction)createMeeting:(id)sender {
    
    API *_api = [API sharedClient];
    UIButton *btn = sender;
    [btn setEnabled:NO];
    [SVProgressHUD showWithStatus:NSLocalizedString(@"CREANDO_REUNION", nil)];
    [_api createMeetingToAssistant:_assistant.idAssistant withOnSuccessHandler:^(NSDictionary *content) {
        [SVProgressHUD showSuccessWithStatus:NSLocalizedString(@"CREANDO_REUNION_OK", nil)];
        [[NSNotificationCenter defaultCenter] postNotificationName:@"updateMeetingsFromApiInAssistantDetail" object:nil userInfo:nil];
        [btn setEnabled:YES];
    } andDuplicateHandler:^(NSInteger statusCode){
        if (statusCode == 406) {
            [SVProgressHUD showErrorWithStatus:NSLocalizedString(@"CREANDO_REUNION_DUPLICADA_ENVIADA", nil)];
        } else if (statusCode == 409) {
            [SVProgressHUD showErrorWithStatus:NSLocalizedString(@"CREANDO_REUNION_DUPLICADA_RECIBIDA", nil)];
        } else {
            [SVProgressHUD showErrorWithStatus:NSLocalizedString(@"CREANDO_REUNION_ERROR", nil)];
        }
        [btn setEnabled:YES];
    } andFailureHandler:^(AFHTTPRequestOperation *operation, NSError *error) {
        [SVProgressHUD showErrorWithStatus:NSLocalizedString(@"CREANDO_REUNION_ERROR", nil)];
        [btn setEnabled:YES];
    }];
}

#pragma mark - UITableViewDataSource

- (NSInteger)tableView:(UITableView *)tableView numberOfRowsInSection:(NSInteger)section {
    
    NSInteger count = 3;
    count = count + interestArray.count;
    return count;
}

- (CGFloat)tableView:(UITableView *)tableView heightForRowAtIndexPath:(NSIndexPath *)indexPath {
    
    if (indexPath.row == interestArray.count + 2) {//last cell
        return 60;
    } else if (indexPath.row == 0) {
        return 165;
    } else if (indexPath.row == 1) {
        if (interestArray.count > 0) {
            return 50;
        } else {
            CGFloat screenHeight = [[UIScreen mainScreen] bounds].size.height;
            CGFloat statusBarHeight = [UIApplication sharedApplication].statusBarFrame.size.height;
            CGFloat navBarHeight = self.navigationController.navigationBar.frame.size.height;
            CGFloat totalHeight = screenHeight - statusBarHeight -navBarHeight - 165 - 60;
            return totalHeight;
        }
    } else {
        return 44;
    }
}

- (UITableViewCell *)tableView:(UITableView *)tableView cellForRowAtIndexPath:(NSIndexPath *)indexPath {
    
    if (indexPath.row == interestArray.count + 2) {//last cell
        AssistantRequestMeetingTableViewCell *cell = [_tableView dequeueReusableCellWithIdentifier:@"assistantRequestMeetingTableViewCell"];
        [cell.btnRequestMeeting setTitle:NSLocalizedString(@"SOLICITAR_REUNION", nil) forState:UIControlStateNormal];
        [cell.btnRequestMeeting addTarget:self action:@selector(createMeeting:) forControlEvents:UIControlEventTouchUpInside];
        [cell.btnRequestMeeting setBackgroundColor:[UIColor navigationBarBackgroundColor]];
        [cell.btnRequestMeeting setTintColor:[UIColor whiteColor]];
        return cell;
    } else if (indexPath.row == 0) {
        AssistantHeaderTableViewCell *cell = [_tableView dequeueReusableCellWithIdentifier:@"assistantHeaderTableViewCell"];
        [cell.imgProfile setImageWithURL:[NSURL URLWithString:_assistant.picUrlCircle] placeholderImage:[UIImage imageNamed:@"placeholder_people.png"]];
        [cell.contentView setBackgroundColor:[UIColor navigationBarBackgroundColor]];
        [cell.lblCompany setText:_assistant.company];
        [cell.lblPosition setText:_assistant.position];
        return cell;
    } else if (indexPath.row == 1) {
        if (interestArray.count > 0) {
            AssistantInterestTitleTableViewCell *cell = [_tableView dequeueReusableCellWithIdentifier:@"assistantInterestTitleTableViewCell"];
            [cell.lblTitle setText:NSLocalizedString(@"TIENE_INTERES", nil)];
            [cell.lblTitle setTextColor:[UIColor navigationBarBackgroundColor]];
            return cell;
        } else {
            UITableViewCell *cell = [[UITableViewCell alloc] initWithStyle:UITableViewCellStyleDefault reuseIdentifier:@"empty"];
            cell.backgroundColor = [UIColor clearColor];
            [cell setUserInteractionEnabled:NO];
            return cell;
        }
    } else {
        AssistantInterestItemTableViewCell *cell = [_tableView dequeueReusableCellWithIdentifier:@"assistantInterestItemTableViewCell"];
        NSString *interest = [interestArray objectAtIndex:indexPath.row - 2];
        [cell.lblTitle setText:interest];
        [cell.switchState setOn:YES];
        [cell.switchState setEnabled:NO];
        [cell.lblTitle setTextColor:[UIColor disabledColor]];
        [cell.switchState setOnTintColor:[UIColor disabledColor]];
        return cell;
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
