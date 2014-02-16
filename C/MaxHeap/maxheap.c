/*
 * ==================================
 * 
 * Konstantinos Konstantopoulos 
 * 
 * konstantok@gmail.com
 * 
 * ================================== 
 * 
 */
#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <assert.h>
#include "maxheap.h"


/* heap representation */
struct maxheap_rep
{
    int* array ;       //array containing properties 
    char* array_c ;    //array containing characters
    int maxsize ;
    int cursize ;
} ;


/* create a new empty heap */
maxheap maxheap_create()
{
    maxheap h = (maxheap) malloc( sizeof( struct maxheap_rep ) ) ;
    if ( !h ) {
        fprintf( stderr , "Not enough memory!\n" ) ;
        abort ( ) ;
    }
    
    h->maxsize = 20;
    h->cursize = 0 ;
    
    h->array = (int * ) malloc( sizeof(int) * (h->maxsize+1) ) ;
    h->array_c = (char * ) malloc( sizeof(char) * (h->maxsize+1) ) ;
    
    if ( !h->array ) {
       fprintf( stderr , "Not enough memory!\n" ) ;
       abort() ;
    }
    
    if ( !h->array_c ) {
       fprintf( stderr , "Not enough memory!\n" ) ;
       abort() ;
    }

    return h ;
}


/* free memory of a heap */
void maxheap_destroy( maxheap H )
{
    assert( H && H->array && H->array_c) ;
    free( H->array ) ;
    free( H->array_c ) ;
    free( H ) ;
}


void maxheap_swap ( maxheap H , int i , int j )
{
    int tmp;
    char tmp1;
    
    assert ( H && i>=1 && i<=H->cursize && j>= 1 && j <= H->cursize ) ;
    
    tmp = H->array[i];
    H->array[i] = H->array[j];
    H->array[j] = tmp;
    
    tmp1 = H->array_c[i];
    H->array_c[i] = H->array_c[j];
    H->array_c[j] = tmp1;
}


/* fix bottom-up the k-th element assuming that
   its priority has been increased */
void maxheap_fixup ( maxheap H , int k )
{
    int tmp ;
    assert ( H && k >= 1 && k <= H->cursize ) ;
    
    while ( k>1 && H->array[k/2]< H->array[k] )
    {
        maxheap_swap ( H , k , k /2 );
        k = k/2 ;
    }
}


/* fix top-down the k-th element assuming that
   its priority has been decreased */
void maxheap_fixdown ( maxheap H , int k )
{
    int tmp ;
    int j ;
    assert ( H ) ;
    
    while ( 2*k <= H->cursize )
    {
        j = 2*k ;
        if ( j < H->cursize && H->array[j]< H->array[j+1])
           j++;
        if ( H->array[k] >= H->array[j])
           break ;
        
        maxheap_swap ( H , k , j ) ;
        k = j;
    }
}


int maxheap_insert( maxheap H , int item , char* item1 )
{  
    assert( H ) ;
    
    if ( H->cursize == H->maxsize ){
       printf("Max heap is full! \nYou can not insert more items!\n\n");
       return 0;
    }
    
    H->cursize++; 
    
    // add at the bottom, as a leaf
    H->array [ H->cursize ] = item ;
    H->array_c [ H->cursize ] = item1[0] ;
    
    // fix its position
    maxheap_fixup( H , H->cursize ) ;
    
    return 1;
}


/* return the max element of a heap */
int maxheap_max( maxheap H )
{
    if ( maxheap_empty(H) ) {
        fprintf(stderr , "Max heap is empty!\n\n" ) ;
        return 0;
    }
    
    printf("Heap's max element is: %c\n\n",  H->array_c[1]);

    return 1 ;
}


int maxheap_deletemax( maxheap H )
{
    if ( maxheap_empty(H) ) {
        fprintf(stderr , "Max heap is empty!\n\n" ) ;
        return 0;
    }
    
    maxheap_swap( H , 1 , H->cursize ) ;
    H->cursize--;
    maxheap_fixdown( H , 1 ) ;
    
    return 1;
}


int maxheap_size( maxheap H )
{
    assert( H ) ;
    return H->cursize ;
}


int maxheap_empty( maxheap H )
{
    assert( H ) ;
    return H->cursize <= 0 ;
}


int main ( )
{
    char input[15];
    
    printf("Creating max heap...");
    maxheap H = maxheap_create();
    printf("\tDone\n\n");
    
    char delims[] = " ";
    char *result = NULL;
    
    while (1){
          printf("What would you like to do?\n[help for list of commands]\n");
          gets(input);
          
          //input[strlen(input)-1] = '\0';
          
          
          result = strtok( input, delims );
          
          if(!strcmp("insert", result)){
               char *item = NULL;
               char *priority = NULL; 
    
               item = strtok( NULL, delims );
               priority = strtok( NULL, delims );
               
               if ( atoi(priority)>=1 && atoi(priority)<=99 ){
                    if( maxheap_insert( H , atoi(priority) , item ))  printf("Item %s with priority %s inserted\n\n", item , priority );
                    continue;
               }
               else{ 
                     printf("Priority out of bounds:\t1 <= p <= 99\n\n");              
                     continue;
               }
          }
          
          if(!strcmp("max", result)){
               maxheap_max(H);
               continue;              
          }
          
          if(!strcmp("extract", result)){
                   result = strtok( NULL, delims );
                   if(!strcmp("max", result)){
                       printf("Heap's max element is being removed... \t");
                       if ( maxheap_deletemax( H ) )       printf("Done\n\n");
                       continue;
                   }
                   else {
                        printf("Please give a valid command\n\n");
                        continue;
                   }
          }
          
          if(!strcmp("size", result)){
               printf("Heap's size is: %d\n\n", maxheap_size(H));
               continue;              
          }
          
          if(!strcmp("exit", result)){
               printf("Destroing heap...\t");
               maxheap_destroy( H );
               printf("Done\n\n");
               printf("Bye bye!\n\n\n");
               break;              
          }
          
          if(!strcmp("help", result)){
               printf("\nList of commands: \ninsert C P \nmax \nextract max \nsize \nexit \n\n");
               continue;
          }

          
          printf("Please give a valid command\n\n");
    }
          
    //sleep(1000);
    
	return 0;
}
