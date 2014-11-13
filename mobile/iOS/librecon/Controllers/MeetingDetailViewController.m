//
//  MeetingDetailViewController.m
//  librecon
//
//  Created by Sergio Garcia on 22/09/14.
//  Copyright (c) 2014 Sergio Garcia. All rights reserved.
//

#import "MeetingDetailViewController.h"
#import "UIImageView+AFNetworking.h"
#import "MeetingAssistant.h"
#import "MeetingCheckedTableViewCell.h"
#import "MeetingSwitchTableViewCell.h"
#import "UIColor+Librecon.h"
#import "API.h"
#import "SVProgressHUD.h"
#import "AppDelegate.h"
#import "MeetingShareTableViewCell.h"

typedef NS_ENUM (NSInteger, MomentRowType) {
    now = 0,
    halfHour = 1,
    hour = 2,
    other = 3
};

typedef NS_ENUM (NSInteger, ShareRowType) {
    email = 0,
    phone = 1
};

@interface MeetingDetailViewController () {
    
    MomentRowType selectedMoment;
    BOOL shareEmail, sharePhone;
    API *_api;
    BOOL uiDisabled;
}

@end

@implementation MeetingDetailViewController

- (void)viewDidLoad {
    
    [super viewDidLoad];
    uiDisabled = NO;
    _api = [API sharedClient];
    [self languajeSetup];
    [self viewSetup];
}

- (void)viewWillAppear:(BOOL)animated {
    
    [[NSNotificationCenter defaultCenter] removeObserver:self
                                                    name:@"updateMeetingFromApi"
                                                  object:nil];
    [[NSNotificationCenter defaultCenter] addObserverForName:@"updateMeetingFromApi"
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
                                                    name:@"updateMeetingFromApi"
                                                  object:nil];
}

- (void)languajeSetup {
    
    [_lblMomentTitle setText:NSLocalizedString(@"CUANDO", nil)];
    if ([_meeting.status isEqualToString:@"accepted"]) {
        [_lblShareTitle setText:NSLocalizedString(@"SHARE_ACCEPT", nil)];
    } else {
        [_lblShareTitle setText:NSLocalizedString(@"SHARE_DEFAULT", nil)];
    }
    [_lblCreateTitle setText:NSLocalizedString(@"FECHA_DE_CREACION", nil)];
    [_lblResponseTitle setText:NSLocalizedString(@"FECHA_DE_RESPUESTA", nil)];
    
    [_lblCreateDate sizeToFit];
    [_lblCreateDate updateConstraintsIfNeeded];
    [_lblResponseDate sizeToFit];
    [_lblResponseDate updateConstraintsIfNeeded];
    
    [_btnCancel setTitle:NSLocalizedString(@"RECHAZAR", nil)
                forState:UIControlStateNormal];
    [_btnAccept setTitle:NSLocalizedString(@"ACEPTAR", nil)
                forState:UIControlStateNormal];
}

- (void)viewSetup {
    
    [self.navigationController.navigationBar setTranslucent:NO];
    [self.navigationController.navigationBar setBackgroundImage:[[UIImage alloc] init] forBarMetrics:UIBarMetricsDefault];
    [self.navigationController.navigationBar setShadowImage:[[UIImage alloc] init]];
    
    selectedMoment = halfHour;
    if ([_meeting.status isEqualToString:@"pending"] && !_meeting.sendedByMe) {
        shareEmail = YES;
        sharePhone = YES;
    } else {
        shareEmail = _meeting.emailShare;
        sharePhone = _meeting.cellphoneShare;
    }
    
    [_lblCreateTitle setTextColor:[UIColor navigationBarBackgroundColor]];
    [_lblCreateDate setTextColor:[UIColor disabledColor]];
    
    [_lblResponseTitle setTextColor:[UIColor navigationBarBackgroundColor]];
    [_lblResponseDate setTextColor:[UIColor disabledColor]];
    
   
    
    [_lblCreateDate setText:[self stringFromDate:_meeting.createdAt]];
    [_lblResponseDate setText:[self stringFromDate:_meeting.responseDate]];
    
    if (!_meeting.responseDate) {
        _contraintResponseViewHeight.constant = 0;
        [_lblResponseTitle setText:@""];
        [_lblResponseDate setText:@""];
    }
    
    [_backgroundView setBackgroundColor:[UIColor navigationBarBackgroundColor]];
    [_lblMomentTitle setTextColor:[UIColor navigationBarBackgroundColor]];
    [_lblShareTitle setTextColor:[UIColor navigationBarBackgroundColor]];
    
    [_btnCancel setBackgroundImage:[UIColor imageWithColor:[UIColor getCancelBackgroundColor]]
                          forState:UIControlStateNormal];
    [_btnCancel setBackgroundImage:[UIColor imageWithColor:[UIColor getCancelHighlightedBackgroundColor]]
                          forState:UIControlStateHighlighted];
    [_btnAccept setBackgroundImage:[UIColor imageWithColor:[UIColor getAcceptBackgroundColor]]
                          forState:UIControlStateNormal];
    [_btnAccept setBackgroundImage:[UIColor imageWithColor:[UIColor getAcceptHighlightedBackgroundColor]]
                          forState:UIControlStateHighlighted];
    
    _scrollView.bounces = NO;
    
    [_tableViewMoment setTintColor:[UIColor navigationBarBackgroundColor]];
    
    _tableViewMoment.dataSource = self;
    _tableViewMoment.delegate = self;
    
    _tableViewShare.dataSource = self;
    _tableViewShare.delegate = self;
    
    if (_profileImage != nil) {
        [_imfProfile setImage:_profileImage];
    } else {
        [_imfProfile setImageWithURL:[NSURL URLWithString:_meeting.assistant.picUrlCircle]
                    placeholderImage:[UIImage imageNamed:@"placeholder_people.png"]];
    }
    
    [self setTitle:[NSString stringWithFormat:@"%@ %@", _meeting.assistant.name, _meeting.assistant.lastName]];
    [_lblCompany setText:_meeting.assistant.company];
    [_lblPosition setText:_meeting.assistant.position];
    
    
    
    [_btnCancel addTarget:self
                   action:@selector(btnCancelAction:)
         forControlEvents:UIControlEventTouchUpInside];
    [_btnAccept addTarget:self
                   action:@selector(btnAcceptAction:)
         forControlEvents:UIControlEventTouchUpInside];
    if ([_meeting.status isEqualToString:@"canceled"]) {
        [self disableInteraction];
        
        sharePhone = NO;
        shareEmail = NO;
        
        [_lblMomentTitle setHidden:YES];
        [_lblShareTitle setHidden:YES];
        [_tableViewMoment removeFromSuperview];
        [_tableViewShare removeFromSuperview];
    } else if (_meeting.sendedByMe || [_meeting.status isEqualToString:@"accepted"]) {
        [self disableInteraction];
        
        BOOL sharing = (shareEmail || sharePhone ? YES : NO);
        if (!sharing) {
            [_tableViewShare removeFromSuperview];
            [_lblShareTitle setText:@""];
        }
        
        [_tableViewMoment setTintColor:[UIColor disabledColor]];
        
        if ([_meeting.moment isEqualToString:@"atRightNow"]) {
            selectedMoment = now;
        } else if ([_meeting.moment isEqualToString:@"atHalfHour"]) {
            selectedMoment = halfHour;
        } else if ([_meeting.moment isEqualToString:@"atOneHour"]) {
            selectedMoment = hour;
        } else {
            selectedMoment = other;
        }
    }
}

- (NSString *)stringFromDate:(NSDate *)date {
    
    if (!date) {
        return @"";
    }
    NSDateFormatter *dateFormatter = [[NSDateFormatter alloc] init];
    [dateFormatter setDateStyle:NSDateFormatterShortStyle];
    [dateFormatter setTimeStyle:NSDateFormatterShortStyle];
    [dateFormatter setLocale:[NSLocale currentLocale]];
    NSString *dateString = [dateFormatter stringFromDate:date];
    
    return dateString;
}

- (void)disableInteraction {
    
    uiDisabled = YES;
    [_btnAccept setEnabled:NO];
    [_btnAccept setHidden:YES];
    [_btnCancel setEnabled:NO];
    [_btnCancel setHidden:YES];
    _shareViewConstraint.constant = 0;
    
    [_lblMomentTitle setTextColor:[UIColor disabledColor]];
    [_lblShareTitle setTextColor:[UIColor disabledColor]];
    
    [_tableViewMoment setUserInteractionEnabled:NO];
}

#pragma mark - UITableViewDataSource

- (NSInteger)tableView:(UITableView *)tableView numberOfRowsInSection:(NSInteger)section {
    
    if (tableView == _tableViewMoment) {
        return 3;
    } else if (tableView == _tableViewShare) {
        NSInteger count = 0;
        if ([_meeting.status isEqualToString:@"accepted"]) {
            count = 2;
        } else {
            count = count + (shareEmail ? 1 : 0);
            count = count + (sharePhone ? 1 : 0);
        }
        if (count == 0) {
            [_tableViewShare setSeparatorStyle:UITableViewCellSeparatorStyleNone];
            [_lblShareTitle setHidden:YES];
        } else {
            CGFloat height = 0;
            height = height + (shareEmail ? 44 : 0);
            height = height + (sharePhone ? 44 : 0);
            _tableViewShareHeightConstraint.constant = height;
        }
        return count;
    } else {
        return 0;
    }
}

- (CGFloat)tableView:(UITableView *)tableView heightForRowAtIndexPath:(NSIndexPath *)indexPath {
    
    if (tableView == _tableViewMoment) {
        return 44;
    } else {
        switch (indexPath.row) {
            case email:
                return (shareEmail ? 44 : 0);
                break;
            case phone:
                return (sharePhone ? 44 : 0);
                break;
            default:
                return 0;
        }
    }
}

- (UITableViewCell *)tableView:(UITableView *)tableView cellForRowAtIndexPath:(NSIndexPath *)indexPath {
    
    if (tableView == _tableViewMoment) {
        MeetingCheckedTableViewCell *cell = [_tableViewMoment dequeueReusableCellWithIdentifier:@"meetingCheckedTableViewCell"];
        if (!cell) {
            cell = [[MeetingCheckedTableViewCell alloc] initWithStyle:UITableViewCellStyleDefault
                                                      reuseIdentifier:@"meetingCheckedTableViewCell"];
        }
        [self configureCell:cell atIndexPath:indexPath inTableView:tableView];
        
        return cell;
    } else if (tableView == _tableViewShare) {
        UITableViewCell *cell ;
        if ([_meeting.status isEqualToString:@"accepted"]) {
            cell = [_tableViewShare dequeueReusableCellWithIdentifier:@"meetingShareTableViewCell"];
        } else {
            cell = [_tableViewShare dequeueReusableCellWithIdentifier:@"meetingSwitchTableViewCell"];
        }
        [self configureCell:cell atIndexPath:indexPath inTableView:tableView];
        
        return cell;
    } else {
        return nil;
    }
}

- (void)configureCell:(UITableViewCell *)cell atIndexPath:(NSIndexPath *)indexPath inTableView:(UITableView *)tableView {
    
    if (tableView == _tableViewMoment) {
        MeetingCheckedTableViewCell *mCell = (MeetingCheckedTableViewCell *)cell;
        [mCell setAccessoryType:(selectedMoment == indexPath.row ? UITableViewCellAccessoryCheckmark : UITableViewCellAccessoryNone)];
        if (_meeting.sendedByMe || [_meeting.status isEqualToString:@"canceled"] || [_meeting.status isEqualToString:@"accepted"]) {
            [mCell.lblTitle setTextColor:[UIColor disabledColor]];
        }
        switch (indexPath.row) {
            case now:
                [mCell.lblTitle setText:NSLocalizedString(@"AHORA", nil)];
                break;
            case halfHour:
                [mCell.lblTitle setText:NSLocalizedString(@"MEDIAHORA", nil)];
                break;
            case hour:
                [mCell.lblTitle setText:NSLocalizedString(@"HORA", nil)];
                break;
        }
    } else if (tableView == _tableViewShare) {
        if ([_meeting.status isEqualToString:@"pending"]) {
            MeetingSwitchTableViewCell *mCell = (MeetingSwitchTableViewCell *)cell;;
            [mCell.switchState setOnTintColor:[UIColor navigationBarBackgroundColor]];
            mCell.switchState.tag = indexPath.row;
            [mCell.switchState addTarget:self action:@selector(switchChanged:) forControlEvents:UIControlEventValueChanged];
            switch (indexPath.row) {
                case email:
                    [mCell.lblTitle setText:NSLocalizedString(@"EMAIL", nil)];
                    break;
                case phone:
                    [mCell.lblTitle setText:NSLocalizedString(@"TELEFONO", nil)];
                    break;
            }
            
        } else if ([_meeting.status isEqualToString:@"accepted"]) {
            MeetingShareTableViewCell *mCell = (MeetingShareTableViewCell *)cell;
            [mCell setSelectionStyle:UITableViewCellSelectionStyleNone];
            [mCell setUserInteractionEnabled:NO];
            [mCell.lblType setTextColor:[UIColor disabledColor]];
            [mCell.lblData setTextColor:[UIColor disabledColor]];
            [_tableViewShare setTintColor:[UIColor disabledColor]];
            
            switch (indexPath.row) {
                case email:
                    if (shareEmail) {
                        [mCell.lblType setText:NSLocalizedString(@"EMAIL", nil)];
                        if (_meeting.sendedByMe) {
                            [mCell setSelectionStyle:UITableViewCellSelectionStyleGray];
                            mCell.userInteractionEnabled = YES;
                            [mCell.lblData setText:(shareEmail ? _meeting.assistant.email : @"")];
                        } else {
                            [mCell setSelectionStyle:UITableViewCellSelectionStyleNone];
                            mCell.userInteractionEnabled = NO;
                            [mCell.lblData removeFromSuperview];
                            [mCell setAccessoryType:UITableViewCellAccessoryCheckmark];
                        }
                        
                    } else {
                        [mCell.lblType removeFromSuperview];
                        [mCell.lblData removeFromSuperview];
                    }
                    break;
                case phone:
                    if (sharePhone) {
                        [mCell.lblType setText:NSLocalizedString(@"TELEFONO", nil)];
                        if (_meeting.sendedByMe) {
                            [mCell setSelectionStyle:UITableViewCellSelectionStyleGray];
                            mCell.userInteractionEnabled = YES;
                            [mCell.lblData setText:(sharePhone ? _meeting.assistant.cellPhone : @"")];
                        } else {
                            [mCell setSelectionStyle:UITableViewCellSelectionStyleNone];
                            mCell.userInteractionEnabled = NO;
                            [mCell.lblData removeFromSuperview];
                            [mCell setAccessoryType:UITableViewCellAccessoryCheckmark];
                        }
                    } else {
                        [mCell.lblType removeFromSuperview];
                        [mCell.lblData removeFromSuperview];
                    }
                    break;
            }
        }
    }
}


- (void)switchChanged:(id)sender {
    
    UISwitch *sw = (UISwitch *)sender;
    switch (sw.tag) {
        case email:
            shareEmail = sw.on;
            break;
        case phone:
            sharePhone = sw.on;
            break;
    }
}

#pragma mark - UITableViewDelegate

- (void)tableView:(UITableView *)tableView didSelectRowAtIndexPath:(NSIndexPath *)indexPath {
    
    if (tableView == _tableViewMoment) {
        NSIndexPath *selectedIndexPath = [NSIndexPath indexPathForRow:selectedMoment inSection:0];
        MeetingCheckedTableViewCell *selectedCell = (MeetingCheckedTableViewCell *)[_tableViewMoment cellForRowAtIndexPath:selectedIndexPath];
        if (selectedCell) {
            [selectedCell setAccessoryType:UITableViewCellAccessoryNone];
        }
        selectedMoment = indexPath.row;
        MeetingCheckedTableViewCell *actualCell = (MeetingCheckedTableViewCell *)[_tableViewMoment cellForRowAtIndexPath:indexPath];
        [actualCell setAccessoryType:UITableViewCellAccessoryCheckmark];
        [_tableViewMoment deselectRowAtIndexPath:indexPath animated:YES];
    } else if (tableView == _tableViewShare) {
        switch (indexPath.row) {
            case email:
                if (shareEmail && _meeting.assistant.email && ![_meeting.assistant.email isEqualToString:@""]) {
                    [[UIApplication sharedApplication] openURL:[NSURL URLWithString:[NSString stringWithFormat:@"mailto:%@", _meeting.assistant.email]]];
                }
                break;
            case phone:
                if (sharePhone && _meeting.assistant.cellPhone && ![_meeting.assistant.cellPhone isEqualToString:@""]) {
                    [[UIApplication sharedApplication] openURL:[NSURL URLWithString:[NSString stringWithFormat:@"telprompt:%@", _meeting.assistant.cellPhone]]];
                }
                break;
            default:
                break;
        }
        [_tableViewShare deselectRowAtIndexPath:indexPath animated:YES];
    }
}

- (IBAction)btnCancelAction:(id)sender {
    
    [SVProgressHUD showWithStatus:nil];
    [_api setMeeting:_meeting.idMeeting withMoment:@"never" andEmailShare:NO andPhoneShare:NO withOnSuccessHandler:^(NSDictionary *content) {
        [SVProgressHUD showSuccessWithStatus:nil];
        [[NSNotificationCenter defaultCenter] postNotificationName:@"updateMeetingFromApi" object:nil userInfo:nil];
    } andFailureHandler:^(AFHTTPRequestOperation *operation, NSError *error) {
        [SVProgressHUD showErrorWithStatus:nil];
    }];
}

- (IBAction)btnAcceptAction:(id)sender {
    
    [SVProgressHUD showWithStatus:nil];
    NSString *moment = @"";
    switch (selectedMoment) {
        case now:
            moment = @"now";
            break;
        case halfHour:
            moment = @"half";
            break;
        case hour:
            moment = @"hour";
            break;
        case other:
            break;
    }
    [_api setMeeting:_meeting.idMeeting withMoment:moment andEmailShare:shareEmail andPhoneShare:sharePhone withOnSuccessHandler:^(NSDictionary *content) {
        [SVProgressHUD showSuccessWithStatus:nil];
        [[NSNotificationCenter defaultCenter] postNotificationName:@"updateMeetingFromApi" object:nil userInfo:nil];
    } andFailureHandler:^(AFHTTPRequestOperation *operation, NSError *error) {
        [SVProgressHUD showErrorWithStatus:nil];
    }];
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
