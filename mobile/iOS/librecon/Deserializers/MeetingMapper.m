//
//  MeetingMapper.m
//  librecon
//
//  Created by Sergio Garcia on 30/09/14.
//  Copyright (c) 2014 Sergio Garcia. All rights reserved.
//

#import "MeetingMapper.h"
#import "JSONToObjectMapper.h"
#import "AppDelegate.h"

@implementation MeetingMapper

+ (void)deserializeSelectedKeysFrom:(NSDictionary *)data toObject:(Meeting *)object fromContext:(NSManagedObjectContext *)context {
    
    NSDateFormatter *df = [[NSDateFormatter alloc] init];
    //2014-11-11 10:30:00
    [df setDateFormat:@"yyyy-MM-dd HH:mm:ss"];
    
    @try {
        object.sendedByMe = [data[@"sendedByMe"] boolValue];
    }
    @catch (NSException *exception) {}
    
    object.createdAt = [df dateFromString: data[@"createdAt"]];
    object.status = data[@"status"];
    if ([object.status isEqualToString:@"pending"]) {
        object.customOrder = [NSNumber numberWithInteger:pending];
    } else if ([object.status isEqualToString:@"accepted"]) {
        object.customOrder = [NSNumber numberWithInteger:accepted];
    } else if ([object.status isEqualToString:@"canceled"]) {
        object.customOrder = [NSNumber numberWithInteger:cancelled];
    } else {
        object.customOrder = [NSNumber numberWithInteger:other];
    }
    @try {
        object.emailShare = [data[@"emailShare"] boolValue];
    }
    @catch (NSException *exception) {}
    
    @try {
        object.cellphoneShare = [data[@"cellphoneShare"] boolValue];
    }
    @catch (NSException *exception) {}
    
    object.moment = data[@"moment"];
    object.responseDate = [df dateFromString: data[@"responseDate"]];
    object.idMeeting = data[@"id"];
    
    MeetingAssistant *assistant = [NSEntityDescription
                                   insertNewObjectForEntityForName:@"MeetingAssistant"
                                   inManagedObjectContext:context];
    
    [JSONToObjectMapper deserializeAllKeys:data[@"assistant"]
                 withFixedIdValueForObject:@"idMeetingAssistant"
                                  toObject:assistant];
    object.assistant = assistant;
}

@end
