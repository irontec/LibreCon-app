//
//  SpeakerMapper.h
//  librecon
//
//  Created by Sergio Garcia on 30/09/14.
//  Copyright (c) 2014 Sergio Garcia. All rights reserved.
//

#import <Foundation/Foundation.h>
#import "Speaker.h"

@interface SpeakerMapper : NSObject

+ (void)deserializeSelectedKeysFrom:(NSDictionary *)data toObject:(Speaker *)object fromContext:(NSManagedObjectContext *)context;
@end
