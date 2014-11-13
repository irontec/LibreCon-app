//
//  Schedule.h
//  librecon
//
//  Created by Sergio Garcia on 16/10/14.
//  Copyright (c) 2014 Sergio Garcia. All rights reserved.
//

#import <Foundation/Foundation.h>
#import <CoreData/CoreData.h>

@class Link, Speaker, Tag;

@interface Schedule : NSManagedObject

@property (nonatomic, retain) NSString * color;
@property (nonatomic, retain) NSString * description_en;
@property (nonatomic, retain) NSString * description_es;
@property (nonatomic, retain) NSString * description_eu;
@property (nonatomic, retain) NSDate * finishDateTime;
@property (nonatomic, retain) NSString * idSchedule;
@property (nonatomic, retain) NSString * location;
@property (nonatomic, retain) NSString * name_en;
@property (nonatomic, retain) NSString * name_es;
@property (nonatomic, retain) NSString * name_eu;
@property (nonatomic, retain) NSString * picUrl;
@property (nonatomic, retain) NSString * picUrlSquare;
@property (nonatomic, retain) NSString * speakersString;
@property (nonatomic, retain) NSDate * startDateTime;
@property (nonatomic, retain) NSString * targetDate;
@property (nonatomic, retain) NSString * tagsString;
@property (nonatomic, retain) NSSet *links;
@property (nonatomic, retain) NSSet *speakers;
@property (nonatomic, retain) NSSet *tags;
@end

@interface Schedule (CoreDataGeneratedAccessors)

- (void)addLinksObject:(Link *)value;
- (void)removeLinksObject:(Link *)value;
- (void)addLinks:(NSSet *)values;
- (void)removeLinks:(NSSet *)values;

- (void)addSpeakersObject:(Speaker *)value;
- (void)removeSpeakersObject:(Speaker *)value;
- (void)addSpeakers:(NSSet *)values;
- (void)removeSpeakers:(NSSet *)values;

- (void)addTagsObject:(Tag *)value;
- (void)removeTagsObject:(Tag *)value;
- (void)addTags:(NSSet *)values;
- (void)removeTags:(NSSet *)values;

@end
