//
//  Photo.m
//  librecon
//
//  Created by Sergio Garcia on 08/10/14.
//  Copyright (c) 2014 Sergio Garcia. All rights reserved.
//

#import "Photo.h"

@implementation Photo

- (void)initWithDictionary:(NSDictionary *)data {
    
    NSDateFormatter *df = [[NSDateFormatter alloc] init];
    [df setDateFormat:@"yyyy-MM-dd HH:mm:ss"];
    _idPhoto      = data[@"id"];
    _title        = data[@"title"];
    _date         = [df dateFromString: data[@"date"]];
    _url          = data[@"url"];
    _thumbnailUrl = data[@"thumbnailUrl"];
}

@end
