//
//  MeetingCheckedTableViewCell.m
//  librecon
//
//  Created by Sergio Garcia on 01/10/14.
//  Copyright (c) 2014 Sergio Garcia. All rights reserved.
//

#import "MeetingCheckedTableViewCell.h"

@implementation MeetingCheckedTableViewCell

- (id)initWithStyle:(UITableViewCellStyle)style reuseIdentifier:(NSString *)reuseIdentifier
{
    self = [super initWithStyle:style reuseIdentifier:reuseIdentifier];
    if (self) {
        // Initialization code
    }
    return self;
}

- (void)awakeFromNib
{
    // Initialization code
}

- (void)setSelected:(BOOL)selected animated:(BOOL)animated
{
    [super setSelected:selected animated:animated];

    // Configure the view for the selected state
}

@end
