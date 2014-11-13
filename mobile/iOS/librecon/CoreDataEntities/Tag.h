//
//  Tag.h
//  librecon
//
//  Created by Sergio Garcia on 18/09/14.
//  Copyright (c) 2014 Sergio Garcia. All rights reserved.
//

#import <Foundation/Foundation.h>
#import <CoreData/CoreData.h>

@class Schedule, Speaker;

@interface Tag : NSManagedObject

@property (nonatomic, retain) NSString * color;
@property (nonatomic, retain) NSString * idTag;
@property (nonatomic, retain) NSString * name_en;
@property (nonatomic, retain) NSString * name_es;
@property (nonatomic, retain) NSString * name_eu;
@property (nonatomic, retain) Schedule *schedule;
@property (nonatomic, retain) Speaker *speaker;

@end
