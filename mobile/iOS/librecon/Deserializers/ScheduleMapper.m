//
//  ScheduleMapper.m
//  librecon
//
//  Created by Sergio Garcia on 30/09/14.
//  Copyright (c) 2014 Sergio Garcia. All rights reserved.
//

#import "ScheduleMapper.h"
#import "JSONToObjectMapper.h"
#import "AppDelegate.h"
#import "SpeakerMapper.h"
#import "Speaker.h"
#import "Tag.h"

@implementation ScheduleMapper

+ (void)deserializeSelectedKeysFrom:(NSDictionary *)data toObject:(Schedule *)object fromContext:(NSManagedObjectContext *)context {
    
    NSDateFormatter *df = [[NSDateFormatter alloc] init];
    //2014-11-11 10:30:00
    [df setDateFormat:@"yyyy-MM-dd HH:mm:ss"];
    
    object.description_en = data[@"description_en"];
    object.description_es = data[@"description_es"];
    object.description_eu = data[@"description_eu"];
    object.finishDateTime = [df dateFromString: data[@"finishDateTime"]];
    object.idSchedule = data[@"id"];
    object.name_en = data[@"name_en"];
    object.name_es = data[@"name_es"];
    object.name_eu = data[@"name_eu"];
    object.picUrl = data[@"picUrl"];
    object.picUrlSquare = data[@"picUrlSquare"];
    object.startDateTime = [df dateFromString: data[@"startDateTime"]];
    object.targetDate = data[@"targetDate"];
    object.location = data[@"location"];
    object.color = data[@"color"];
    
    object.speakersString = @"";
    NSArray *speakerData = data[@"speakers"];
    for (NSDictionary *speakerItem in speakerData) {
        Speaker *speaker = [NSEntityDescription
                            insertNewObjectForEntityForName:@"Speaker"
                            inManagedObjectContext:context];
        [SpeakerMapper deserializeSelectedKeysFrom:speakerItem
                                          toObject:speaker
                                       fromContext:context];
        [object addSpeakersObject:speaker];
        object.speakersString = [object.speakersString stringByAppendingString:[NSString stringWithFormat:@" %@", speaker.name]];
    }
    
    NSArray *linksData = data[@"links"];
    for (NSDictionary *linkItem in linksData) {
        Link *link = [NSEntityDescription
                      insertNewObjectForEntityForName:@"Link"
                      inManagedObjectContext:context];
        [JSONToObjectMapper deserializeAllKeys:linkItem
                     withFixedIdValueForObject:@"idLink"
                                      toObject:link];
        [object addLinksObject:link];
    }
    
    object.tagsString = @"";
    NSArray *tagsData = data[@"tags"];
    for (NSDictionary *tagItem in tagsData) {
        Tag *tag = [NSEntityDescription
                    insertNewObjectForEntityForName:@"Tag"
                    inManagedObjectContext:context];
        [JSONToObjectMapper deserializeAllKeys:tagItem
                     withFixedIdValueForObject:@"idTag"
                                      toObject:tag];
        [object addTagsObject:tag];
        object.tagsString = [object.tagsString stringByAppendingString:[NSString stringWithFormat:@" %@ %@ %@", tag.name_en, tag.name_es, tag.name_eu]];
    }

}
@end
