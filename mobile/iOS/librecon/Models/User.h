//
//  User.h
//  librecon
//
//  Created by Sergio Garcia on 23/09/14.
//  Copyright (c) 2014 Sergio Garcia. All rights reserved.
//

#import <Foundation/Foundation.h>

@interface User : NSObject

@property (nonatomic, strong) NSString *idUser;
@property (nonatomic, strong) NSString *hashUser;
@property (nonatomic, strong) NSString *name;
@property (nonatomic, strong) NSString *lastName;
@property (nonatomic, strong) NSString *email;
@property (nonatomic, strong) NSString *cellPhone;
@property (nonatomic, strong) NSString *company;
@property (nonatomic, strong) NSString *position;
@property (nonatomic, strong) NSString *picUrl;
@property (nonatomic, strong) NSString *picUrlCircle;
@property (nonatomic, strong) NSString *address;
@property (nonatomic, strong) NSString *location;
@property (nonatomic, strong) NSString *country;
@property (nonatomic, strong) NSString *postalCode;
@property (nonatomic, strong) NSString *interests;

- (void)initWithDictionary:(NSDictionary *)data;
@end
