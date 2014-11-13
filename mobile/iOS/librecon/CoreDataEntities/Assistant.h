//
//  Assistant.h
//  librecon
//
//  Created by Sergio Garcia on 15/10/14.
//  Copyright (c) 2014 Sergio Garcia. All rights reserved.
//

#import <Foundation/Foundation.h>
#import <CoreData/CoreData.h>


@interface Assistant : NSManagedObject

@property (nonatomic, retain) NSString * address;
@property (nonatomic, retain) NSString * cellPhone;
@property (nonatomic, retain) NSString * company;
@property (nonatomic, retain) NSString * country;
@property (nonatomic, retain) NSString * email;
@property (nonatomic, retain) NSString * idAssistant;
@property (nonatomic, retain) NSString * interests;
@property (nonatomic, retain) NSString * lastName;
@property (nonatomic, retain) NSString * location;
@property (nonatomic, retain) NSString * name;
@property (nonatomic, retain) NSString * picUrl;
@property (nonatomic, retain) NSString * picUrlCircle;
@property (nonatomic, retain) NSString * position;
@property (nonatomic, retain) NSString * postalCode;

@end
