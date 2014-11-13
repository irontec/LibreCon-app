//
//  API.m
//  librecon
//
//  Created by Sergio Garcia on 16/09/14.
//  Copyright (c) 2014 Sergio Garcia. All rights reserved.
//

#import "API.h"
#import "UserDefaultsHelper.h"
#import "AppDelegate.h"

#define AUTHENTICATE_CODE @"auth/code"
#define REQUEST_CODE @"auth/mail"

#define FORCE_LOGIN_STATUS_CODE 401

@interface API()

@property (strong, nonatomic) AFHTTPRequestOperationManager *manager;

@end

@implementation API

-(id)init {
    
    if (!(self = [super init]))
        return self;
    self.manager = [[AFHTTPRequestOperationManager alloc] initWithBaseURL:[NSURL URLWithString:kAppApiURL]];
    
    /* Set security for validate certificate public key */
    AFSecurityPolicy *securityPolicy = [[AFSecurityPolicy alloc] init];
    securityPolicy.SSLPinningMode = AFSSLPinningModePublicKey;
    securityPolicy.validatesCertificateChain = NO;
    
    NSString *pathToCertificate = [[NSBundle mainBundle] pathForResource:kAppProdCertName ofType:@"cer"];
    NSData *certificate = [NSData dataWithContentsOfFile:pathToCertificate];
    securityPolicy.pinnedCertificates = @[certificate];
    [securityPolicy setAllowInvalidCertificates:NO];
    self.manager.securityPolicy = securityPolicy;
    
    return self;
}


+ (API *)sharedClient {
    
    static API *_sharedInstance = nil;
    static dispatch_once_t oncePredicate;
    dispatch_once(&oncePredicate, ^{
        _sharedInstance = [[API alloc] init];
        NSString *hash = [UserDefaultsHelper getUserHash];
        if (hash && ![hash isEqualToString:@""]) {
            [_sharedInstance.manager.requestSerializer setValue:hash forHTTPHeaderField:@"Authorization"];
        }
    });
    return _sharedInstance;
}

+ (void)setCustomHeader {
    
    NSString *hash = [UserDefaultsHelper getUserHash];
    if (hash && ![hash isEqualToString:@""]) {
        [[self sharedClient].manager.requestSerializer setValue:hash forHTTPHeaderField:@"Authorization"];
    }
}

- (void)forceUserLogin {
    
    [UserDefaultsHelper deleteAllDefaults];
    AppDelegate *app = (AppDelegate *)[[UIApplication sharedApplication] delegate];
    [app deleteAllDAtaFromDatabase];
    [app loadLoginController];
}

- (void)athenticateWithCode:(NSString *)code withOnSuccessHandler:(APISuccess)successHandler andFailureHandler:(APIFailure)failureHandler {
    
    NSMutableDictionary *data = [[NSMutableDictionary alloc] init];
    data[@"code"] = code;

    [_manager POST:AUTHENTICATE_CODE parameters:data success:^(AFHTTPRequestOperation *operation, id responseObject) {
        NSInteger statusCode = [operation.response statusCode];
        NSLog(@"AUTHENTICATE_CODE POST SUCCESS with code: %ld", (long)statusCode);
        
        NSString *userHash = responseObject[@"data"][@"assistant"][@"hash"];
        if (!userHash || userHash.length == 0) {
            NSLog(@"======================================================================================");
            NSLog(@"User hash is empty. Some error ocurred");
            NSLog(@"======================================================================================");
            NSError *error = [NSError errorWithDomain:@"User hash is empty" code:statusCode userInfo:nil];
            failureHandler(operation, error);
        } else {
            [UserDefaultsHelper setAnonymous:NO];
            successHandler(responseObject);
        }
    } failure:^(AFHTTPRequestOperation *operation, NSError *error) {
        NSLog(@"AUTHENTICATE_CODE POST ERROR with code: %ld", (long)[operation.response statusCode]);
        failureHandler(operation, error);
    }];
}

- (void)requestCodeWithEmail:(NSString *)email withOnSuccessHandler:(APISuccess)successHandler andFailureHandler:(APIFailure)failureHandler {
    
    NSMutableDictionary *data = [[NSMutableDictionary alloc] init];
    data[@"email"] = email;

    [_manager POST:REQUEST_CODE parameters:data success:^(AFHTTPRequestOperation *operation, id responseObject) {
        NSLog(@"REQUEST_CODE POST SUCCESS with code: %ld", (long)[operation.response statusCode]);
        successHandler(responseObject);
    } failure:^(AFHTTPRequestOperation *operation, NSError *error) {
        NSLog(@"REQUEST_CODE POST ERROR with code: %ld", (long)[operation.response statusCode]);
        failureHandler(operation, error);
    }];
}

- (void)sendUUID:(NSString *)uuid {
    
    if (!uuid) {
        uuid = @"";
    }
    NSDictionary *data = [[NSDictionary alloc] initWithObjectsAndKeys:uuid, @"uuid",
                                                                    @"ios", @"device",
                                                                    [UserDefaultsHelper getActualLanguage], @"lang",
                                                                    nil];
    
    NSLog(@"data: %@", data);
    [_manager PUT:ASSISTANTS parameters:data success:^(AFHTTPRequestOperation *operation, id responseObject) {
        NSInteger statusCode = [operation.response statusCode];
        NSLog(@"ASSISTANTS PUT SUCCESS with code: %ld", (long)statusCode);
    } failure:^(AFHTTPRequestOperation *operation, NSError *error) {
        NSInteger statusCode = [operation.response statusCode];
        NSLog(@"ASSISTANTS PUT ERROR with code: %ld", (long)statusCode);
    }];
}

- (void)getSchedulesWithOnSuccessHandler:(APISuccess)successHandler andFailureHandler:(APIFailure)failureHandler {
    
    NSString *version = [UserDefaultsHelper getVersionForType:SCHEDULES];
    NSDictionary *data = [[NSDictionary alloc] initWithObjectsAndKeys:version, @"v", nil];
    
    [_manager GET:SCHEDULES parameters:data success:^(AFHTTPRequestOperation *operation, id responseObject) {
        NSInteger statusCode = [operation.response statusCode];
        NSLog(@"SCHEDULES GET SUCCESS with code: %ld", (long)statusCode);
        if (statusCode != 304) {//modified
            [UserDefaultsHelper setVersion:responseObject[@"data"][@"version"] forType:SCHEDULES];
            successHandler(responseObject);
        }
    } failure:^(AFHTTPRequestOperation *operation, NSError *error) {
        NSInteger statusCode = [operation.response statusCode];
        NSLog(@"SCHEDULES GET ERROR with code: %ld", (long)statusCode);
        if (statusCode == FORCE_LOGIN_STATUS_CODE) {
            [self forceUserLogin];
        } else if (statusCode != 304 && statusCode != 0) {//modified
            NSLog(@"Version removed because code != 304");
            [UserDefaultsHelper setVersion:@"" forType:SCHEDULES];
        }
        failureHandler(operation, error);
    }];
}

- (void)getAssistentsWithOnSuccessHandler:(APISuccess)successHandler andFailureHandler:(APIFailure)failureHandler {
    
    NSString *version = [UserDefaultsHelper getVersionForType:ASSISTANTS];
    NSDictionary *data = [[NSDictionary alloc] initWithObjectsAndKeys:version, @"v", nil];
    
    [_manager GET:ASSISTANTS parameters:data success:^(AFHTTPRequestOperation *operation, id responseObject) {
        NSInteger statusCode = [operation.response statusCode];
        NSLog(@"ASSISTANTS GET SUCCESS with code: %ld", (long)statusCode);
        if (statusCode != 304 && statusCode != 0) {//modified
            [UserDefaultsHelper setVersion:responseObject[@"data"][@"version"] forType:ASSISTANTS];
            successHandler(responseObject);
        }
    } failure:^(AFHTTPRequestOperation *operation, NSError *error) {
        NSInteger statusCode = [operation.response statusCode];
        NSLog(@"ASSISTANTS GET ERROR with code: %ld", (long)statusCode);
        if (statusCode == FORCE_LOGIN_STATUS_CODE) {
            [self forceUserLogin];
        } else if (statusCode != 304 && statusCode != 0) {//modified
            NSLog(@"Version removed because code != 304");
            [UserDefaultsHelper setVersion:@"" forType:ASSISTANTS];
        }
        failureHandler(operation, error);
    }];
}

- (void)getTxokosWithOnSuccessHandler:(APISuccess)successHandler andFailureHandler:(APIFailure)failureHandler {
    
    NSString *version = [UserDefaultsHelper getVersionForType:TXOKOS];
    NSDictionary *data = [[NSDictionary alloc] initWithObjectsAndKeys:version, @"v", nil];
    
    [_manager GET:TXOKOS parameters:data success:^(AFHTTPRequestOperation *operation, id responseObject) {
        NSInteger statusCode = [operation.response statusCode];
        NSLog(@"TXOKOS GET SUCCESS with code: %ld", (long)statusCode);
        if (statusCode != 304) {//modified
            [UserDefaultsHelper setVersion:responseObject[@"data"][@"version"] forType:TXOKOS];
            successHandler(responseObject);
        }
    } failure:^(AFHTTPRequestOperation *operation, NSError *error) {
        NSInteger statusCode = [operation.response statusCode];
        NSLog(@"TXOKOS GET ERROR with code: %ld", (long)statusCode);
        if (statusCode == FORCE_LOGIN_STATUS_CODE) {
            [self forceUserLogin];
        } else if (statusCode != 304 && statusCode != 0) {//modified
            NSLog(@"Version removed because code != 304");
            [UserDefaultsHelper setVersion:@"" forType:TXOKOS];
        }
        failureHandler(operation, error);
    }];
}

- (void)getStandsWithOnSuccessHandler:(APISuccess)successHandler andFailureHandler:(APIFailure)failureHandler {
    
    NSString *version = [UserDefaultsHelper getVersionForType:STANDS];
    NSDictionary *data = [[NSDictionary alloc] initWithObjectsAndKeys:version, @"v", nil];
    
    [_manager GET:STANDS parameters:data success:^(AFHTTPRequestOperation *operation, id responseObject) {
        NSInteger statusCode = [operation.response statusCode];
        NSLog(@"STANDS GET SUCCESS with code: %ld", (long)statusCode);
        if (statusCode != 304) {//modified
            [UserDefaultsHelper setVersion:responseObject[@"data"][@"version"] forType:STANDS];
            successHandler(responseObject);
        }
    } failure:^(AFHTTPRequestOperation *operation, NSError *error) {
        NSInteger statusCode = [operation.response statusCode];
        NSLog(@"STANDS GET ERROR with code: %ld", (long)statusCode);
        if (statusCode == FORCE_LOGIN_STATUS_CODE) {
            [self forceUserLogin];
        } else if (statusCode != 304 && statusCode != 0) {//modified
            NSLog(@"Version removed because code != 304");
            [UserDefaultsHelper setVersion:@"" forType:STANDS];
        }
        failureHandler(operation, error);
    }];
}

- (void)getMeetingsWithOnSuccessHandler:(APISuccess)successHandler andFailureHandler:(APIFailure)failureHandler {
    
    NSString *version = [UserDefaultsHelper getVersionForType:MEETINGS];
    
    NSDictionary *data;
    if (version && ![version isEqualToString:@""]) {
        data = [[NSDictionary alloc] initWithObjectsAndKeys:version, @"v", nil];
    }
    
    [_manager GET:MEETINGS parameters:data success:^(AFHTTPRequestOperation *operation, id responseObject) {
        NSInteger statusCode = [operation.response statusCode];
        NSLog(@"MEETINGS GET SUCCESS with code: %ld", (long)statusCode);
        if (statusCode != 304) {//modified
            [UserDefaultsHelper setVersion:responseObject[@"data"][@"version"] forType:MEETINGS];
            successHandler(responseObject);
        }
    } failure:^(AFHTTPRequestOperation *operation, NSError *error) {
        NSInteger statusCode = [operation.response statusCode];
        NSLog(@"MEETINGS GET ERROR with code: %ld", (long)statusCode);
        if (statusCode == FORCE_LOGIN_STATUS_CODE) {
            [self forceUserLogin];
        } else if (statusCode != 304 && statusCode != 0) {//modified
            NSLog(@"Version removed because code != 304");
            [UserDefaultsHelper setVersion:@"" forType:MEETINGS];
        }
        failureHandler(operation, error);
    }];
}

- (void)getMeetingWithId:(NSString *)idMeeting WithOnSuccessHandler:(APISuccess)successHandler andFailureHandler:(APIFailure)failureHandler {
    
    NSString *finalString = [NSString stringWithFormat:@"%@/%@", MEETINGS, idMeeting];
    [_manager GET:finalString parameters:nil success:^(AFHTTPRequestOperation *operation, id responseObject) {
        NSInteger statusCode = [operation.response statusCode];
        NSLog(@"MEETINGS(%@) GET SUCCESS with code: %ld", idMeeting, (long)statusCode);
        successHandler(responseObject);
    } failure:^(AFHTTPRequestOperation *operation, NSError *error) {
        NSInteger statusCode = [operation.response statusCode];
        NSLog(@"MEETINGS(%@) GET ERROR with code: %ld", idMeeting, (long)statusCode);
        if (statusCode == FORCE_LOGIN_STATUS_CODE) {
            [self forceUserLogin];
        }
        failureHandler(operation, error);
    }];
}

- (void)createMeetingToAssistant:(NSString *)idAssistant withOnSuccessHandler:(APISuccess)successHandler andDuplicateHandler:(APIMeetingDuplicated)duplicateHandler andFailureHandler:(APIFailure)failureHandler {
    
    NSMutableDictionary *data = [[NSMutableDictionary alloc] init];
    data[@"receiver"] = idAssistant;
    [_manager POST:MEETINGS parameters:data success:^(AFHTTPRequestOperation *operation, id responseObject) {
        NSInteger statusCode = [operation.response statusCode];
        NSLog(@"MEETINGS POST SUCCESS with code: %ld", (long)statusCode);
        successHandler(responseObject);
        AppDelegate *app = (AppDelegate *)[[UIApplication sharedApplication] delegate];
        [app checkMeetings];
    } failure:^(AFHTTPRequestOperation *operation, NSError *error) {
        NSInteger statusCode = [operation.response statusCode];
        NSLog(@"MEETINGS POST ERROR with code: %ld", (long)statusCode);
        if (statusCode == FORCE_LOGIN_STATUS_CODE) {
            [self forceUserLogin];
        } else if (statusCode == 406) {//ya he enviado una yo
            duplicateHandler(statusCode);
        } else if (statusCode == 409) {//me han enviado ya una
            duplicateHandler(statusCode);
        } else {
            failureHandler(operation, error);
        }
        
    }];
}

- (void)setMeeting:(NSString *)meetingId withMoment:(NSString *)moment andEmailShare:(BOOL)emailShare andPhoneShare:(BOOL)phoneShare withOnSuccessHandler:(APISuccess)successHandler andFailureHandler:(APIFailure)failureHandler {
    
    NSMutableDictionary *data = [[NSMutableDictionary alloc] init];
    data[@"meetingId"] = meetingId;
    data[@"moment"] = moment;
    data[@"emailShare"] = [NSNumber numberWithBool:emailShare];
    data[@"cellphoneShare"] = [NSNumber numberWithBool:phoneShare];
    
    [_manager PUT:MEETINGS parameters:data success:^(AFHTTPRequestOperation *operation, id responseObject) {
        NSInteger statusCode = [operation.response statusCode];
        NSLog(@"MEETINGS PUT SUCCESS with code: %ld", (long)statusCode);
        successHandler(responseObject);
    } failure:^(AFHTTPRequestOperation *operation, NSError *error) {
        NSInteger statusCode = [operation.response statusCode];
        NSLog(@"MEETINGS PUT ERROR with code: %ld", (long)statusCode);
        //403 es que estoy respondiendo a un meeting que he creado yo
        if (statusCode == FORCE_LOGIN_STATUS_CODE) {
            [self forceUserLogin];
        } else {
            failureHandler(operation, error);
        }
    }];
}

- (void)getSponsorsWithOnSuccessHandler:(APISuccess)successHandler andFailureHandler:(APIFailure)failureHandler {
    
    NSString *version = [UserDefaultsHelper getVersionForType:SPONSORS];
    
    NSDictionary *data;
    if (version && ![version isEqualToString:@""]) {
        data = [[NSDictionary alloc] initWithObjectsAndKeys:version, @"v", nil];
    }
    
    [_manager GET:SPONSORS parameters:data success:^(AFHTTPRequestOperation *operation, id responseObject) {
        NSInteger statusCode = [operation.response statusCode];
        NSLog(@"SPONSORS GET SUCCESS with code: %ld", (long)statusCode);
        if (statusCode != 304) {//modified
            [UserDefaultsHelper setVersion:responseObject[@"data"][@"version"] forType:SPONSORS];
            successHandler(responseObject);
        }
    } failure:^(AFHTTPRequestOperation *operation, NSError *error) {
        NSInteger statusCode = [operation.response statusCode];
        NSLog(@"SPONSORS GET ERROR with code: %ld", (long)statusCode);
        if (statusCode == FORCE_LOGIN_STATUS_CODE) {
            [self forceUserLogin];
        } else if (statusCode != 304 && statusCode != 0) {//modified
            NSLog(@"Version removed because code != 304");
            [UserDefaultsHelper setVersion:@"" forType:SPONSORS];
        }
        failureHandler(operation, error);
    }];
}

- (void)getPhotosWithOnSuccessHandler:(APISuccess)successHandler andFailureHandler:(APIFailure)failureHandler {
    
    [_manager GET:PHOTOCALL parameters:nil success:^(AFHTTPRequestOperation *operation, id responseObject) {
        NSInteger statusCode = [operation.response statusCode];
        NSLog(@"PHOTOCALL GET SUCCESS with code: %ld", (long)statusCode);
            successHandler(responseObject);
    } failure:^(AFHTTPRequestOperation *operation, NSError *error) {
        NSInteger statusCode = [operation.response statusCode];
        NSLog(@"PHOTOCALL GET ERROR with code: %ld", (long)statusCode);
        if (statusCode == FORCE_LOGIN_STATUS_CODE) {
            [self forceUserLogin];
        }
        failureHandler(operation, error);
    }];
}

@end
