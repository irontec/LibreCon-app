//
//  JTSImageInfo.m
//  
//
//  Created by Jared Sinclair on 3/2/14.
//  Copyright (c) 2014 Nice Boy LLC. All rights reserved.
//

#import "JTSImageInfo.h"

@implementation JTSImageInfo

- (NSMutableDictionary *)userInfo {
    if (_userInfo == nil) {
        _userInfo = [[NSMutableDictionary alloc] init];
    }
    return _userInfo;
}

- (CGPoint)referenceRectCenter {
    return CGPointMake(self.referenceRect.origin.x + self.referenceRect.size.width/2.0f,
                       self.referenceRect.origin.y + self.referenceRect.size.height/2.0f);
}

@end





