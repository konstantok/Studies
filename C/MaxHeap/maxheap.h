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
#ifndef _MAXHEAP_H
#define _MAXHEAP_H


typedef struct maxheap_rep* maxheap ;

maxheap maxheap_create ( ) ;
void maxheap_destroy( maxheap ) ;

int maxheap_max( maxheap ) ;
int maxheap_insert( maxheap , int , char* ) ;
int maxheap_deletemax( maxheap ) ;

int maxheap_size ( maxheap ) ;
int maxheap_empty( maxheap ) ;

#endif
