//
//  LinkTableViewCell.m
//  librecon
//
//  Created by Sergio Garcia on 06/10/14.
//  Copyright (c) 2014 Sergio Garcia. All rights reserved.
//

#import "LinkTableViewCell.h"

@implementation LinkTableViewCell

- (void)awakeFromNib {
    // Initialization code
}

- (void)setSelected:(BOOL)selected animated:(BOOL)animated {
    [super setSelected:selected animated:animated];
    // Configure the view for the selected state
}

- (void)setLinkType:(NSString *)type {
    
    [_lblLink setText:[type capitalizedString]];
    
    if ([type isEqualToString:@"facebook"]) {
        [_imgLink setImage:[UIImage imageNamed:@"facebook.png"]];
    } else if ([type isEqualToString:@"github"]) {
        [_imgLink setImage:[UIImage imageNamed:@"github"]];
    } else if ([type isEqualToString:@"info"]) {
        [_imgLink setImage:[UIImage imageNamed:@"info"]];
    } else if ([type isEqualToString:@"slideshare"]) {
        [_imgLink setImage:[UIImage imageNamed:@"slideshare"]];
    } else if ([type isEqualToString:@"twitter"]) {
        [_imgLink setImage:[UIImage imageNamed:@"twitter"]];
    } else if ([type isEqualToString:@"youtube"]) {
        [_imgLink setImage:[UIImage imageNamed:@"youtube"]];
    } else if ([type isEqualToString:@"linkedin"]) {
        [_imgLink setImage:[UIImage imageNamed:@"linkedin"]];
    }
}

@end
