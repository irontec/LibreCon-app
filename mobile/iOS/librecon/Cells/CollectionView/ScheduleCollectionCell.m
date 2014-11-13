//
//  ScheduleCollectionCell.m
//  librecon
//
//  Created by Sergio Garcia on 17/09/14.
//  Copyright (c) 2014 Sergio Garcia. All rights reserved.
//

#import "ScheduleCollectionCell.h"

@implementation ScheduleCollectionCell

- (id)initWithFrame:(CGRect)frame
{
    self = [super initWithFrame:frame];
    if (self) {
        // Initialization code
    }
    return self;
}

- (void)setBounds:(CGRect)bounds {//fix ios7
    
    [super setBounds:bounds];
    self.contentView.frame = bounds;
}

@end
