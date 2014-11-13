//
//  MeetingDetailViewController.h
//  librecon
//
//  Created by Sergio Garcia on 22/09/14.
//  Copyright (c) 2014 Sergio Garcia. All rights reserved.
//

#import <UIKit/UIKit.h>
#import "Meeting.h"

@interface MeetingDetailViewController : UIViewController <UITableViewDataSource, UITableViewDelegate>

@property (nonatomic, strong) Meeting *meeting;
@property (nonatomic, strong) UIImage *profileImage;

@property (weak, nonatomic) IBOutlet UIView *backgroundView;

@property (weak, nonatomic) IBOutlet UIImageView *imfProfile;
@property (weak, nonatomic) IBOutlet UILabel *lblName;
@property (weak, nonatomic) IBOutlet UILabel *lblCompany;
@property (weak, nonatomic) IBOutlet UILabel *lblPosition;

@property (weak, nonatomic) IBOutlet UILabel *lblCreateTitle;
@property (weak, nonatomic) IBOutlet UILabel *lblCreateDate;
@property (weak, nonatomic) IBOutlet NSLayoutConstraint *constraintCreateViewHeight;
@property (weak, nonatomic) IBOutlet UILabel *lblResponseTitle;
@property (weak, nonatomic) IBOutlet UILabel *lblResponseDate;
@property (weak, nonatomic) IBOutlet NSLayoutConstraint *contraintResponseViewHeight;
@property (weak, nonatomic) IBOutlet UIScrollView *scrollView;

@property (weak, nonatomic) IBOutlet UILabel *lblMomentTitle;
@property (weak, nonatomic) IBOutlet UITableView *tableViewMoment;

@property (weak, nonatomic) IBOutlet UILabel *lblShareTitle;
@property (weak, nonatomic) IBOutlet UITableView *tableViewShare;

@property (weak, nonatomic) IBOutlet NSLayoutConstraint *shareViewConstraint;
@property (weak, nonatomic) IBOutlet UIButton *btnCancel;
@property (weak, nonatomic) IBOutlet UIButton *btnAccept;

@property (weak, nonatomic) IBOutlet NSLayoutConstraint *tableViewShareHeightConstraint;
@property (weak, nonatomic) IBOutlet NSLayoutConstraint *createTimeWidthConstraint;
@property (weak, nonatomic) IBOutlet NSLayoutConstraint *responseTimeWidthConstraint;
@end
