//
//  Sponsor.h
//  librecon
//
//  Created by Sergio Garcia on 29/10/14.
//  Copyright (c) 2014 Sergio Garcia. All rights reserved.
//

#import <Foundation/Foundation.h>
#import <CoreData/CoreData.h>


@interface Sponsor : NSManagedObject

@property (nonatomic, retain) NSString * idSponsor;
@property (nonatomic, retain) NSString * name_en;
@property (nonatomic, retain) NSString * name_es;
@property (nonatomic, retain) NSString * name_eu;
@property (nonatomic, retain) NSString * picUrl;
@property (nonatomic, retain) NSString * url;
@property (nonatomic, retain) NSNumber * orderField;

@end
