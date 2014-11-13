//
//  Stand.h
//  librecon
//
//  Created by Sergio Garcia on 29/10/14.
//  Copyright (c) 2014 Sergio Garcia. All rights reserved.
//

#import <Foundation/Foundation.h>
#import <CoreData/CoreData.h>


@interface Stand : NSManagedObject

@property (nonatomic, retain) NSString * companyName;
@property (nonatomic, retain) NSString * description_en;
@property (nonatomic, retain) NSString * description_es;
@property (nonatomic, retain) NSString * description_eu;
@property (nonatomic, retain) NSString * idStand;
@property (nonatomic, retain) NSString * picUrl;
@property (nonatomic, retain) NSNumber * orderField;

@end
