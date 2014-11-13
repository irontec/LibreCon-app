//
//  SpeakerMapper.m
//  librecon
//
//  Created by Sergio Garcia on 30/09/14.
//  Copyright (c) 2014 Sergio Garcia. All rights reserved.
//

#import "SpeakerMapper.h"
#import "JSONToObjectMapper.h"
#import "AppDelegate.h"

@implementation SpeakerMapper

+ (void)deserializeSelectedKeysFrom:(NSDictionary *)data toObject:(Speaker *)object fromContext:(NSManagedObjectContext *)context {
    
    object.company = data[@"company"];
    object.description_en = data[@"description_en"];
    object.description_es = data[@"description_es"];
    object.description_eu = data[@"description_eu"];
    object.idSpeaker = data[@"id"];
    object.name = data[@"name"];
    object.picUrl = data[@"picUrl"];
    object.picUrlCircle = data[@"picUrlCircle"];
    object.schedule = data[@"schedule"];
    
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
    
    NSArray *tagsData = data[@"tags"];
    for (NSDictionary *tagItem in tagsData) {
        Tag *tag = [NSEntityDescription
                    insertNewObjectForEntityForName:@"Tag"
                    inManagedObjectContext:context];
        [JSONToObjectMapper deserializeAllKeys:tagItem
                     withFixedIdValueForObject:@"idTag"
                                      toObject:tag];
        [object addTagsObject:tag];
    }
}

@end
