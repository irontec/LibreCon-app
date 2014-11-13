//
//  Speaker.h
//  librecon
//
//  Created by Sergio Garcia on 18/09/14.
//  Copyright (c) 2014 Sergio Garcia. All rights reserved.
//

#import <Foundation/Foundation.h>
#import <CoreData/CoreData.h>

@class Link, Schedule, Tag;

@interface Speaker : NSManagedObject

@property (nonatomic, retain) NSString * company;
@property (nonatomic, retain) NSString * description_en;
@property (nonatomic, retain) NSString * description_es;
@property (nonatomic, retain) NSString * description_eu;
@property (nonatomic, retain) NSString * idSpeaker;
@property (nonatomic, retain) NSString * name;
@property (nonatomic, retain) NSString * picUrl;
@property (nonatomic, retain) NSString * picUrlCircle;
@property (nonatomic, retain) Schedule *schedule;
@property (nonatomic, retain) NSSet *links;
@property (nonatomic, retain) NSSet *tags;

@end

@interface Speaker (CoreDataGeneratedAccessors)

- (void)addLinksObject:(Link *)value;
- (void)removeLinksObject:(Link *)value;
- (void)addLinks:(NSSet *)values;
- (void)removeLinks:(NSSet *)values;

- (void)addTagsObject:(Tag *)value;
- (void)removeTagsObject:(Tag *)value;
- (void)addTags:(NSSet *)values;
- (void)removeTags:(NSSet *)values;

@end
