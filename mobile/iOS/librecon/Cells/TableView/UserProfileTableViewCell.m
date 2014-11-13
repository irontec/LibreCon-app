//
//  UserProfileTableViewCell.m
//  librecon
//
//  Created by Sergio Garcia on 17/09/14.
//  Copyright (c) 2014 Sergio Garcia. All rights reserved.
//

#import "UserProfileTableViewCell.h"

@implementation UserProfileTableViewCell

- (id)initWithStyle:(UITableViewCellStyle)style reuseIdentifier:(NSString *)reuseIdentifier
{
    self = [super initWithStyle:style reuseIdentifier:reuseIdentifier];
    if (self) {
        // Initialization code
    }
    return self;
}

- (void)drawRect:(CGRect)rect {
    
    _imgProfile.layer.masksToBounds = YES;
    _imgProfile.layer.cornerRadius = _imgProfile.frame.size.width / 2;
    _imgProfile.layer.borderWidth = 1.0;
    _imgProfile.layer.borderColor = [UIColor whiteColor].CGColor;
}

- (void)setSelected:(BOOL)selected animated:(BOOL)animated
{
    [super setSelected:selected animated:animated];

    // Configure the view for the selected state
}

@end
