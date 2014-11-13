//
//  LocationDetailViewController.m
//  librecon
//
//  Created by Sergio Garcia on 20/10/14.
//  Copyright (c) 2014 Sergio Garcia. All rights reserved.
//

#import "LocationDetailViewController.h"
#import "ImageTableViewCell.h"
#import "LabelTableViewCell.h"
#import "UIImageView+AFNetworking.h"

@interface LocationDetailViewController ()

@end

@implementation LocationDetailViewController

- (void)viewDidLoad {
    
    [super viewDidLoad];
    _tableView.dataSource = self;
    _tableView.delegate = self;
}

- (NSInteger)tableView:(UITableView *)tableView numberOfRowsInSection:(NSInteger)section {
    
    return 2;
}

- (CGFloat)tableView:(UITableView *)tableView heightForRowAtIndexPath:(NSIndexPath *)indexPath {
    
    if (indexPath.row == 0) {
        CGFloat val = (_tableView.frame.size.width / 3) * 2;//600*400
        return val;
    } else {
        LabelTableViewCell *cell = [_tableView dequeueReusableCellWithIdentifier:@"labelTableViewCell"];
        [cell.lblDesc setText:_text];
        CGRect textRect = [cell.lblDesc.text boundingRectWithSize:CGSizeMake(cell.lblDesc.frame.size.width, FLT_MAX)
                                                                 options:NSStringDrawingUsesLineFragmentOrigin
                                                              attributes:@{NSFontAttributeName:cell.lblDesc.font}
                                                                 context:nil];
        return textRect.size.height + 16 + 1;
    }
}

- (UITableViewCell *)tableView:(UITableView *)tableView cellForRowAtIndexPath:(NSIndexPath *)indexPath {
    
    if (indexPath.row == 0) {
        ImageTableViewCell *cell = [_tableView dequeueReusableCellWithIdentifier:@"imageTableViewCell"];
        [cell.imgTop setImageWithURL:[NSURL URLWithString:_picUrl] placeholderImage:[UIImage imageNamed:@"placeholder_librecon.png"]];
        return cell;
    } else {
        LabelTableViewCell *cell = [_tableView dequeueReusableCellWithIdentifier:@"labelTableViewCell"];
        [cell.lblDesc setText:_text];
        return cell;
    }
}


@end
