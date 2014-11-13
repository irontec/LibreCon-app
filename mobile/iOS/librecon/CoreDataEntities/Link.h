//
//  Link.h
//  librecon
//
//  Created by Sergio Garcia on 18/09/14.
//  Copyright (c) 2014 Sergio Garcia. All rights reserved.
//

#import <Foundation/Foundation.h>
#import <CoreData/CoreData.h>

@class Schedule, Speaker;

@interface Link : NSManagedObject

@property (nonatomic, retain) NSString * url;
@property (nonatomic, retain) NSString * type;
@property (nonatomic, retain) Schedule *schedule;
@property (nonatomic, retain) Speaker *speaker;

@end
