//
//  StandMapper.h
//  librecon
//
//  Created by Sergio Garcia on 30/09/14.
//  Copyright (c) 2014 Sergio Garcia. All rights reserved.
//

#import <Foundation/Foundation.h>
#import "Meeting.h"

typedef NS_ENUM (NSInteger, customOrder) {
    pending = 1,
    accepted = 2,
    cancelled = 3,
    other = 4
};

@interface MeetingMapper : NSObject

+ (void)deserializeSelectedKeysFrom:(NSDictionary *)data toObject:(Meeting *)object fromContext:(NSManagedObjectContext *)context;
@end
