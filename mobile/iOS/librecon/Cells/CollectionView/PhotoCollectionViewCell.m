//
//  PhotoCollectionViewCell.m
//  librecon
//
//  Created by Sergio Garcia on 08/10/14.
//  Copyright (c) 2014 Sergio Garcia. All rights reserved.
//

#import "PhotoCollectionViewCell.h"
#define MINIMUM_SCALE 1
#define MAXIMUM_SCALE 50

@implementation PhotoCollectionViewCell

- (void)setBounds:(CGRect)bounds {//fix ios7
    
    [super setBounds:bounds];
    self.contentView.frame = bounds;
}

@end
