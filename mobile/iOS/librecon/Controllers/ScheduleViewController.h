//
//  AgendaTabBarViewController.h
//  librecon
//
//  Created by Sergio Garcia on 16/09/14.
//  Copyright (c) 2014 Sergio Garcia. All rights reserved.
//

#import <UIKit/UIKit.h>
#import "ParentViewController.h"
#import "SWRevealViewController.h"

@interface ScheduleViewController : ParentViewController <UICollectionViewDataSource, UICollectionViewDelegate, UITabBarDelegate, NSFetchedResultsControllerDelegate, UISearchBarDelegate>

@property (weak, nonatomic) IBOutlet UIBarButtonItem *revealButtonItem;

@property (weak, nonatomic) IBOutlet UICollectionView *collectionView;

@property (weak, nonatomic) IBOutlet UITabBar *tabBar;

@property (nonatomic,strong) NSManagedObjectContext* managedObjectContextDay1;
@property (nonatomic,strong) NSManagedObjectContext* managedObjectContextDay2;
@property (nonatomic, strong) NSFetchedResultsController *currentFetchedResultsController;
@property (nonatomic, strong) NSFetchedResultsController *fetchedResultsController_DayOne;
@property (nonatomic, strong) NSFetchedResultsController *fetchedResultsController_DayTwo;
@end
