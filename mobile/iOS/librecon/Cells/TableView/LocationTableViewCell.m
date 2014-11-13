
//
//  TxokoTableViewCell.m
//  librecon
//
//  Created by Sergio Garcia on 19/09/14.
//  Copyright (c) 2014 Sergio Garcia. All rights reserved.
//

#import "LocationTableViewCell.h"

@implementation LocationTableViewCell {
    
    UIColor *lblBackgroundColor;
}

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

- (void)setSelected:(BOOL)selected animated:(BOOL)animated {
    
//    [super setSelected:selected animated:animated];
    // Configure the view for the selected state
}

- (void)setHighlighted:(BOOL)highlighted animated:(BOOL)animated {
    
    UIColor *backColor;
    if (highlighted) {
        backColor = [UIColor grayColor];
    } else {
        backColor = [UIColor clearColor];
    }
    if (animated) {
        [UIView animateWithDuration:0.5 animations:^{
            [self setBackgroundColor:backColor];
        }];
    } else {
        [self setBackgroundColor:backColor];
    }
}

@end
