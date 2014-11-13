//
//  SponsorMapper.h
//  librecon
//
//  Created by Sergio Garcia on 30/09/14.
//  Copyright (c) 2014 Sergio Garcia. All rights reserved.
//

#import <Foundation/Foundation.h>

@interface JSONToObjectMapper : NSObject

+ (void)deserialize:(NSDictionary *)data withKeyValues:(NSDictionary *)keyValues toObject:(id)object;
+ (void)deserializeAllKeys:(NSDictionary *)data toObject:(id)object;
+ (void)deserializeAllKeys:(NSDictionary *)data withFixedIdValueForObject:(NSString *)idValue toObject:(id)object;
@end
