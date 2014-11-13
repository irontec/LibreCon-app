//
//  LocationsViewController.m
//  librecon
//
//  Created by Sergio Garcia on 19/09/14.
//  Copyright (c) 2014 Sergio Garcia. All rights reserved.
//

#import "LocationsViewController.h"
#import "AppDelegate.h"
#import "UserDefaultsHelper.h"
#import "LocationTableViewCell.h"
#import "Txoko.h"
#import "Stand.h"
#import "UIImageView+AFNetworking.h"
#import "UIColor+Librecon.h"
#import "LocationDetailViewController.h"

typedef NS_ENUM (NSInteger, dataSourceType) {
    txoko = 0,
    stand = 1
};

@interface LocationsViewController() {
    
    NSString *appLanguaje;
    dataSourceType dataSource;
    
    BOOL isLoadingTxokos, isLoadingStands;
    UIRefreshControl *refreshControl;
}

@end

@implementation LocationsViewController

- (void)viewDidLoad {
    
    [super viewDidLoad];
    
    isLoadingTxokos = isLoadingStands = NO;
    
    appLanguaje = [UserDefaultsHelper getActualLanguage];
    [self menuSetup];
    [self languajeSetup];
    [self viewSetup];
}

- (void)viewWillAppear:(BOOL)animated {
    
    [[NSNotificationCenter defaultCenter] removeObserver:self
                                                    name:NOTIFI_TXOKOS_UPDATED
                                                  object:nil];
    [[NSNotificationCenter defaultCenter] addObserver:self
                                             selector:@selector(updateTxokosNotificationReceived:)
                                                 name:NOTIFI_TXOKOS_UPDATED
                                               object:nil];
    
    [[NSNotificationCenter defaultCenter] removeObserver:self
                                                    name:NOTIFI_STAND_UPDATED
                                                  object:nil];
    [[NSNotificationCenter defaultCenter] addObserver:self
                                             selector:@selector(updateStandsNotificationReceived:)
                                                 name:NOTIFI_STAND_UPDATED
                                               object:nil];
}

- (void)updateTxokosNotificationReceived:(NSNotification*)notification {
    
    NSDictionary *data = [notification object];
    NSNumber *res = data[@"result"];
    if ([res boolValue]) {
        if (dataSource == txoko) {
            [self loadResultsController_Txokos];
            [_tableView reloadData];
        }
    }
    NSLog(@"Updating txokos datasource..");
    isLoadingTxokos = NO;
    if (!isLoadingStands) {//check other datausource because use the same table
        [refreshControl endRefreshing];//stop when all datasources are updated
    }
    
}

- (void)updateStandsNotificationReceived:(NSNotification*)notification {
    
    NSDictionary *data = [notification object];
    NSNumber *res = data[@"result"];
    if ([res boolValue]) {
        if (dataSource == stand) {
            [self loadResultsController_Stands];
            [_tableView reloadData];
        }
    }
    NSLog(@"Updating stands datasource..");
    isLoadingStands = NO;
    if (!isLoadingTxokos) {//check other datausource because use the same table
        [refreshControl endRefreshing];//stop when all datasources are updated
    }
}

- (void)viewDidAppear:(BOOL)animated {
    
    id sectionInfo = [[_currentFetchedResultsController sections] objectAtIndex:0];
    NSInteger count = [sectionInfo numberOfObjects];
    
    if (!count || count == 0) {
        [_tableView setSeparatorStyle:UITableViewCellSeparatorStyleNone];
    } else {
        [_tableView setSeparatorStyle:UITableViewCellSeparatorStyleSingleLine];
    }
    
    if (count == 0) {
        NSLog(@"Show loading view..");
        _tableView.contentOffset = CGPointMake(0, - refreshControl.frame.size.height);
        [refreshControl beginRefreshing];
    }
    AppDelegate *app = (AppDelegate *)[[UIApplication sharedApplication] delegate];
    [app checkStands];
    [app checkTxokos];
}

- (void)viewWillDisappear:(BOOL)animated {
    
    [[NSNotificationCenter defaultCenter] removeObserver:self
                                                    name:NOTIFI_TXOKOS_UPDATED
                                                  object:nil];
    [[NSNotificationCenter defaultCenter] removeObserver:self
                                                    name:NOTIFI_STAND_UPDATED
                                                  object:nil];
}

- (void)dealloc {
    
    _fetchedResultsController_Txokos = nil;
    _fetchedResultsController_Stands = nil;
}

- (void)menuSetup {
    
    SWRevealViewController *revealViewController = self.revealViewController;
    if (revealViewController) {
        [self.revealButtonItem setTarget:revealViewController];
        [self.revealButtonItem setAction:@selector(revealToggle:)];
        [self.navigationController.navigationBar addGestureRecognizer:revealViewController.panGestureRecognizer];
    }
}

- (void)languajeSetup {
    
}

- (void)viewSetup {
    
    self.navigationItem.backBarButtonItem = [[UIBarButtonItem alloc] initWithTitle:@""
                                                                             style:UIBarButtonItemStylePlain
                                                                            target:nil
                                                                            action:nil];
    
    AppDelegate *appDelegate = (AppDelegate *)[[UIApplication sharedApplication] delegate];
    
    NSManagedObjectContext *globalContext = [appDelegate managedObjectContext];
    
    _managedObjectContext = [[NSManagedObjectContext alloc] initWithConcurrencyType:NSPrivateQueueConcurrencyType];
    [_managedObjectContext setPersistentStoreCoordinator:globalContext.persistentStoreCoordinator];
    
    _tabBar.delegate = self;
    _tableView.dataSource = self;
    _tableView.delegate = self;
    
    [self setBackgroundEmptyView];
    [self tabbarSetup];
    [self dataControllerSetup];
    
    [_tabBar setSelectedItem:_tabBar.items[txoko]];
    dataSource = txoko;
    [self setTitle:NSLocalizedString(@"TXOKOS", nil)];
    [self reloadModeSetup];
    [self reloadTableViewCustom];
}

- (void)setBackgroundEmptyView {
    
    UILabel *label = [[UILabel alloc] initWithFrame:_tableView.frame];
    [label setNumberOfLines:4];
    [label setText:NSLocalizedString(@"NO_DATA_LOCATION", nil)];
    [label setTextAlignment:NSTextAlignmentCenter];
    [label setTextColor:[UIColor tableViewBackgroundTextColor]];
    [label sizeToFit];
    [_tableView setBackgroundView:label];
}

- (void)tabbarSetup {
    
    UITabBarItem *txokos, *stands;
    
    txokos = [_tabBar items][0];
    stands = [_tabBar items][1];
    
    [txokos setTitle:NSLocalizedString(@"TXOKOS", nil)];
    [stands setTitle:NSLocalizedString(@"EXPOSITORES", nil)];
    
    [txokos setImage:[[UIImage imageNamed:@"tabbar_txoko_unselected"] imageWithRenderingMode:UIImageRenderingModeAlwaysOriginal]];
    [txokos setSelectedImage:[[UIImage imageNamed:@"tabbar_txoko_selected"] imageWithRenderingMode:UIImageRenderingModeAlwaysOriginal]];
    
    [stands setImage:[[UIImage imageNamed:@"tabbar_expositor_unselected"] imageWithRenderingMode:UIImageRenderingModeAlwaysOriginal]];
    [stands setSelectedImage:[[UIImage imageNamed:@"tabbar_expositor_selected"] imageWithRenderingMode:UIImageRenderingModeAlwaysOriginal]];
}

#pragma mark - Reload Setup

- (void)reloadModeSetup {
    
    refreshControl = [[UIRefreshControl alloc] init];
    [refreshControl setBackgroundColor:[UIColor whiteColor]];
    
    [refreshControl addTarget:self
                       action:@selector(refresh:)
             forControlEvents:UIControlEventValueChanged];
    
    NSMutableAttributedString *aString = [[NSMutableAttributedString alloc] initWithString:NSLocalizedString(@"CARGANDO", nil)];
    [aString addAttribute:NSForegroundColorAttributeName
                    value:[UIColor grayCustomColor]
                    range:NSMakeRange(0,aString.length)];
    [refreshControl setAttributedTitle:aString];
    
    [self.tableView addSubview:refreshControl];
}

- (void)refresh:(UIRefreshControl *)refreshControl {
    
    if (isLoadingTxokos || isLoadingStands) {
        return;
    }
    isLoadingTxokos = isLoadingStands = YES;//update all datasource because they use the same tableview
    AppDelegate *delegate = (AppDelegate *)[UIApplication sharedApplication].delegate;
    [delegate checkTxokos];
    [delegate checkStands];
}

- (void)reloadTableViewCustom {
    
    if (dataSource == txoko) {
        _currentFetchedResultsController = _fetchedResultsController_Txokos;
    } else {
        _currentFetchedResultsController = _fetchedResultsController_Stands;
    }
    [_tableView reloadData];
    [_tableView setContentOffset:CGPointZero animated:YES];
}

#pragma mark - UITabBarDelegate

- (void)tabBar:(UITabBar *)tabBar didSelectItem:(UITabBarItem *)item {
    
    NSInteger indexOfTab = [tabBar.items indexOfObject:item];
    switch (indexOfTab) {
        case txoko:
            dataSource = txoko;
            [self setTitle:NSLocalizedString(@"TXOKOS", nil)];
            break;
        case stand:
            dataSource = stand;
            [self setTitle:NSLocalizedString(@"EXPOSITORES", nil)];
            break;
        default:
            dataSource = txoko;
            [self setTitle:NSLocalizedString(@"TXOKOS", nil)];
            break;
    }
    [self reloadTableViewCustom];
}

#pragma mark - Data Control

- (void)dataControllerSetup {
    
    [self loadResultsController_Stands];
    [self loadResultsController_Txokos];
}

- (void)loadResultsController_Txokos {
    
    NSError *error;
    if (![[self fetchResultsController_Txokos] performFetch:&error]) {
        NSLog(@"Txokos controller. Unresolved error %@, %@", error, [error userInfo]);
    }
}

- (void)loadResultsController_Stands {
    
    NSError *error;
    if (![[self fetchResultsController_Stands] performFetch:&error]) {
        NSLog(@"Stand controller. Unresolved error %@, %@", error, [error userInfo]);
    }
}

- (NSFetchedResultsController *)fetchResultsController_Txokos {
    
    if (_fetchedResultsController_Txokos != nil) {
        return _fetchedResultsController_Txokos;
    }
    
    NSFetchRequest *mfetchRequest = [[NSFetchRequest alloc] init];
    NSEntityDescription *mentity = [NSEntityDescription entityForName:IDEN_TXOKO
                                               inManagedObjectContext:_managedObjectContext];
    
    [mfetchRequest setEntity:mentity];
    
    NSString *order = @"orderField";
    NSSortDescriptor *msort = [[NSSortDescriptor alloc] initWithKey:order
                                                          ascending:YES];
    
    [mfetchRequest setSortDescriptors:[NSArray arrayWithObject:msort]];
    [mfetchRequest setFetchBatchSize:20];
    
    NSFetchedResultsController *theFetchedResultsController = [[NSFetchedResultsController alloc] initWithFetchRequest:mfetchRequest
                                                                                                  managedObjectContext:_managedObjectContext
                                                                                                    sectionNameKeyPath:nil
                                                                                                             cacheName:CACHE_TXOKOS];
    
    _fetchedResultsController_Txokos = theFetchedResultsController;
    
    _fetchedResultsController_Txokos.delegate = self;
    return _fetchedResultsController_Txokos;
}

- (NSFetchedResultsController *)fetchResultsController_Stands {
    
    if (_fetchedResultsController_Stands != nil) {
        return _fetchedResultsController_Stands;
    }
    
    NSFetchRequest *mfetchRequest = [[NSFetchRequest alloc] init];
    NSEntityDescription *mentity = [NSEntityDescription entityForName:IDEN_STAND
                                               inManagedObjectContext:_managedObjectContext];
    
    [mfetchRequest setEntity:mentity];
    
    NSString *order = @"orderField";
    NSSortDescriptor *msort = [[NSSortDescriptor alloc] initWithKey:order
                                                          ascending:YES];
    
    [mfetchRequest setSortDescriptors:[NSArray arrayWithObject:msort]];
    [mfetchRequest setFetchBatchSize:20];
    
    NSFetchedResultsController *theFetchedResultsController = [[NSFetchedResultsController alloc] initWithFetchRequest:mfetchRequest
                                                                                                  managedObjectContext:_managedObjectContext
                                                                                                    sectionNameKeyPath:nil
                                                                                                             cacheName:CACHE_STAND];
    
    _fetchedResultsController_Stands = theFetchedResultsController;
    
    _fetchedResultsController_Stands.delegate = self;
    return _fetchedResultsController_Stands;
}


#pragma mark - UITableViewDataSource

- (NSInteger)tableView:(UITableView *)tableView numberOfRowsInSection:(NSInteger)section {
    
    id sectionInfo = [[_currentFetchedResultsController sections] objectAtIndex:section];
    NSInteger count = [sectionInfo numberOfObjects];
    if (count == 0) {
        [_tableView.backgroundView setHidden:NO];
    } else {
        [_tableView.backgroundView setHidden:YES];
    }
    return count;
}

- (CGFloat)tableView:(UITableView *)tableView heightForRowAtIndexPath:(NSIndexPath *)indexPath {
    
    CGFloat val = (_tableView.frame.size.width / 3) * 2;//600*400
    return val;
}

- (UITableViewCell *)tableView:(UITableView *)tableView cellForRowAtIndexPath:(NSIndexPath *)indexPath {
    
    UITableViewCell *cell;
    cell = [_tableView dequeueReusableCellWithIdentifier:@"locationTableViewCell"];
    [self configureCell:cell atIndexPath:indexPath];
    
    return cell;
}

- (void)configureCell:(UITableViewCell *)cell atIndexPath:(NSIndexPath *)indexPath {
    
    LocationTableViewCell *mCell = (LocationTableViewCell *)cell;
    [mCell setSelectionStyle:UITableViewCellSelectionStyleNone];
    if (_currentFetchedResultsController == _fetchedResultsController_Txokos) {
        Txoko *t = [_fetchedResultsController_Txokos objectAtIndexPath:indexPath];
        [mCell.imgBack setImageWithURL:[NSURL URLWithString:t.picUrl] placeholderImage:[UIImage imageNamed:@"placeholder_librecon.png"]];
        
        if ([appLanguaje isEqualToString:@"en"]) {
            [mCell.lblTitle setText:t.title_en];
        } else if ([appLanguaje isEqualToString:@"eu"]) {
            [mCell.lblTitle setText:t.title_eu];
        } else {
            [mCell.lblTitle setText:t.title_es];
        }
    } else {
        Stand *s = [_fetchedResultsController_Stands objectAtIndexPath:indexPath];
        [mCell.imgBack setImageWithURL:[NSURL URLWithString:s.picUrl] placeholderImage:[UIImage imageNamed:@"placeholder_librecon.png"]];
        [mCell.lblTitle setText:s.companyName];
    }
}

#pragma mark - UITableViewDelegate

- (void)tableView:(UITableView *)tableView didSelectRowAtIndexPath:(NSIndexPath *)indexPath {
    NSMutableDictionary *data = [[NSMutableDictionary alloc] init];
    if (_currentFetchedResultsController == _fetchedResultsController_Txokos) {
         Txoko *t = [_fetchedResultsController_Txokos objectAtIndexPath:indexPath];

        data[@"picUrl"] = t.picUrl;
        
        if ([appLanguaje isEqualToString:@"en"]) {
            data[@"title"] = t.title_en;
            data[@"text"] = t.text_en;
        } else if ([appLanguaje isEqualToString:@"eu"]) {
            data[@"title"] = t.title_eu;
            data[@"text"] = t.text_eu;
        } else {
            data[@"title"] = t.title_es;
            data[@"text"] = t.text_es;
        }
    } else {
        Stand *s = [_fetchedResultsController_Stands objectAtIndexPath:indexPath];
        
        data[@"picUrl"] = s.picUrl;
        data[@"title"] = s.companyName;
        
        if ([appLanguaje isEqualToString:@"en"]) {
            data[@"text"] = s.description_en;
        } else if ([appLanguaje isEqualToString:@"eu"]) {
            data[@"text"] = s.description_eu;
        } else {
            data[@"text"] = s.description_es;
        }
    }
    [self performSegueWithIdentifier:@"openDetail" sender:data];
    [_tableView deselectRowAtIndexPath:indexPath animated:YES];
}

#pragma mark - Navigation

- (void)prepareForSegue:(UIStoryboardSegue *)segue sender:(id)sender {
    
    if ([[segue identifier] isEqualToString:@"openDetail"]) {
        NSDictionary *data = (NSDictionary *)sender;
        LocationDetailViewController *locationDetail = (LocationDetailViewController *)[segue destinationViewController];
        locationDetail.detailTitle = data[@"title"];
        locationDetail.picUrl = data[@"picUrl"];
        locationDetail.text = data[@"text"];
    }
}

#pragma mark - NSFetchedResultsControllerDelegate

- (void)controllerWillChangeContent:(NSFetchedResultsController *)controller {
    
    [self.tableView beginUpdates];
}

- (void)controller:(NSFetchedResultsController *)controller didChangeObject:(id)anObject atIndexPath:(NSIndexPath *)indexPath forChangeType:(NSFetchedResultsChangeType)type newIndexPath:(NSIndexPath *)newIndexPath {
    
    UITableView *tableView = self.tableView;
    switch(type) {
            
        case NSFetchedResultsChangeInsert:
            [tableView insertRowsAtIndexPaths:[NSArray arrayWithObject:newIndexPath] withRowAnimation:UITableViewRowAnimationFade];
            break;
            
        case NSFetchedResultsChangeDelete:
            [tableView deleteRowsAtIndexPaths:[NSArray arrayWithObject:indexPath] withRowAnimation:UITableViewRowAnimationFade];
            break;
            
        case NSFetchedResultsChangeUpdate:
            [self configureCell:[tableView cellForRowAtIndexPath:indexPath] atIndexPath:indexPath];
            break;
            
        case NSFetchedResultsChangeMove:
            [tableView deleteRowsAtIndexPaths:[NSArray arrayWithObject:indexPath] withRowAnimation:UITableViewRowAnimationFade];
            [tableView insertRowsAtIndexPaths:[NSArray arrayWithObject:newIndexPath] withRowAnimation:UITableViewRowAnimationFade];
            break;
    }
}

- (void)controller:(NSFetchedResultsController *)controller didChangeSection:(id )sectionInfo atIndex:(NSUInteger)sectionIndex forChangeType:(NSFetchedResultsChangeType)type {
    
    switch(type) {
        case NSFetchedResultsChangeInsert:
            [self.tableView insertSections:[NSIndexSet indexSetWithIndex:sectionIndex] withRowAnimation:UITableViewRowAnimationFade];
            break;
            
        case NSFetchedResultsChangeDelete:
            [self.tableView deleteSections:[NSIndexSet indexSetWithIndex:sectionIndex] withRowAnimation:UITableViewRowAnimationFade];
            break;
    }
}

- (void)controllerDidChangeContent:(NSFetchedResultsController *)controller {
    
    [self.tableView endUpdates];
}

#pragma mark - Rotation

- (BOOL)shouldAutorotateToInterfaceOrientation:(UIInterfaceOrientation)toInterfaceOrientation
{
    return (toInterfaceOrientation == UIInterfaceOrientationPortrait);
}

- (BOOL)shouldAutorotate
{
    return YES;
}

- (NSUInteger)supportedInterfaceOrientations
{
    return UIInterfaceOrientationMaskPortrait;
}

- (UIInterfaceOrientation)preferredInterfaceOrientationForPresentation
{
    return UIInterfaceOrientationPortrait;
}

@end
