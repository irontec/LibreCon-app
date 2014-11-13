//
//  TagCollectionViewCell.m
//  librecon
//
//  Created by Sergio Garcia on 06/10/14.
//  Copyright (c) 2014 Sergio Garcia. All rights reserved.
//

#import "TagCollectionViewCell.h"

@implementation TagCollectionViewCell

- (void)setBounds:(CGRect)bounds {//fix ios7
    
    [super setBounds:bounds];
    self.contentView.frame = bounds;
}

@end
