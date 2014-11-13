//
//  LocationsViewController.h
//  librecon
//
//  Created by Sergio Garcia on 19/09/14.
//  Copyright (c) 2014 Sergio Garcia. All rights reserved.
//

#import <UIKit/UIKit.h>
#import "ParentViewController.h"
#import "SWRevealViewController.h"

@interface LocationsViewController : ParentViewController <UITableViewDataSource, UITableViewDelegate, UITabBarDelegate, NSFetchedResultsControllerDelegate>

@property (weak, nonatomic) IBOutlet UIBarButtonItem *revealButtonItem;
@property (weak, nonatomic) IBOutlet UITableView *tableView;

@property (weak, nonatomic) IBOutlet UITabBar *tabBar;

@property (nonatomic,strong) NSManagedObjectContext* managedObjectContext;
@property (nonatomic, strong) NSFetchedResultsController *currentFetchedResultsController;
@property (nonatomic, strong) NSFetchedResultsController *fetchedResultsController_Txokos;
@property (nonatomic, strong) NSFetchedResultsController *fetchedResultsController_Stands;
@end
