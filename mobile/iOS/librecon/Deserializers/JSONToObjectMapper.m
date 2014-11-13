
//
//  SponsorMapper.m
//  librecon
//
//  Created by Sergio Garcia on 30/09/14.
//  Copyright (c) 2014 Sergio Garcia. All rights reserved.
//

#import "JSONToObjectMapper.h"

@implementation JSONToObjectMapper


+ (void)deserialize:(NSDictionary *)data withKeyValues:(NSDictionary *)keyValues toObject:(id)object {
    
    for (NSString *key in [keyValues allKeys]) {
        NSString *value = keyValues[key];
        
        NSObject *dataObject = data[value];
        if ([object respondsToSelector:NSSelectorFromString(key)] && dataObject) {
            [object setValue:dataObject forKey:key];
        }
    }
}

+ (void)deserializeAllKeys:(NSDictionary *)data toObject:(id)object {
    
    [JSONToObjectMapper deserializeAllKeys:data
                 withFixedIdValueForObject:nil
                                  toObject:object];
}

+ (void)deserializeAllKeys:(NSDictionary *)data withFixedIdValueForObject:(NSString *)idValue toObject:(id)object {
    
    for (NSString *key in data) {
        NSString *fixedKey = key;
        if ([key isEqualToString:@"id"] && idValue) {
            fixedKey = idValue;
        }
        NSString *value = [data valueForKey:key];
        if (!value || [value isKindOfClass:[NSNull class]]) {
            value = @"";
        }
        if ([object respondsToSelector:NSSelectorFromString(fixedKey)] && value) {
            [object setValue:value forKey:fixedKey];
        }
    }
}

@end
