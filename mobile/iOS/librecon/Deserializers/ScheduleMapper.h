//
//  ScheduleMapper.h
//  librecon
//
//  Created by Sergio Garcia on 30/09/14.
//  Copyright (c) 2014 Sergio Garcia. All rights reserved.
//

#import <Foundation/Foundation.h>
#import "Schedule.h"

@interface ScheduleMapper : NSObject

+ (void)deserializeSelectedKeysFrom:(NSDictionary *)data toObject:(Schedule *)object fromContext:(NSManagedObjectContext *)context;
@end
