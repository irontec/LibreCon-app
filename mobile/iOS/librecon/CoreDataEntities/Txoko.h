//
//  Txoko.h
//  librecon
//
//  Created by Sergio Garcia on 29/10/14.
//  Copyright (c) 2014 Sergio Garcia. All rights reserved.
//

#import <Foundation/Foundation.h>
#import <CoreData/CoreData.h>


@interface Txoko : NSManagedObject

@property (nonatomic, retain) NSString * idTxoko;
@property (nonatomic, retain) NSString * picUrl;
@property (nonatomic, retain) NSString * text_en;
@property (nonatomic, retain) NSString * text_es;
@property (nonatomic, retain) NSString * text_eu;
@property (nonatomic, retain) NSString * title_en;
@property (nonatomic, retain) NSString * title_es;
@property (nonatomic, retain) NSString * title_eu;
@property (nonatomic, retain) NSNumber * orderField;

@end
