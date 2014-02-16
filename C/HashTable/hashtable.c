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


struct hash_table
{
    int* array ;       //array of hash table
} ;


typedef struct hash_table* hashtable ;


hashtable hash_table_create(int m)
{
    int i=0 ;
    
    hashtable hashtbl = (hashtable) malloc( sizeof( struct hash_table ) ) ;
    if ( !hashtbl ) {
        fprintf( stderr , "Not enough memory!\n" ) ;
        abort ( ) ;
    }
    
    hashtbl->array = (int * ) malloc( sizeof(int) * m ) ;
    
    
    if ( !hashtbl->array ) {
       fprintf( stderr , "Not enough memory!\n" ) ;
       abort() ;
    }
    
    for( i=0 ; i<m ; i++)
    {
            hashtbl->array[i] = -1; //intial value -1 
            //printf("ok%d\n", i);
    }


    return hashtbl ;
}


void hashtable_destroy( hashtable hashtbl )
{
    free( hashtbl->array ) ;
    free( hashtbl ) ;
}


int hashtable_find( hashtable hashtbl , int key , int m )
{
    int i=0 , steps = 0, found=0;
    
    for( i=key%m ; i<m ; i++)
    {
            steps++;
            if( hashtbl->array[i] == key )
            {
                found = 1;
                break;
            }
            if( hashtbl->array[i] == -1 )
            {
                printf("Key %d not found in hash table (%d steps)\n",  key, steps);    
                return found ;
            }    
    }
    if(!found)
        for( i=0 ; i<key%m ; i++)
        {                
                steps++;
                if( hashtbl->array[i] == key )
                {
                    found = 1;
                    break;
                }     
                if( hashtbl->array[i] == -1 )    break;      
        }
    //printf("found=%d\n", found);
    
    if( !found )
        printf("Key %d not found in hash table (%d steps)\n",  key, steps);
    else
        printf("Key %d found in hash table after %d steps\n",  key, steps);


    return found ;
}


void hashtable_insert( hashtable hashtbl , int key , int m )
{  
    int i=key%m , steps = 0, done = 0;
    
    if(hashtable_find( hashtbl , key , m ))
         return;
    else
    {
        for( i=key%m ; i<m ; i++)
        {
                steps++;
                if( hashtbl->array[i] == -1 )
                {
                    hashtbl->array[i] = key;
                    done = 1;
                    break;
                }          
        }
        
        if(!done)
            for( i=0 ; i<key%m ; i++)
            {
                    steps++;
                    if( hashtbl->array[i] == -1 )
                    {
                        hashtbl->array[i] = key;
                        done = 1;
                        break;
                    }         
            }
    }
    
    if(done)    printf("Key %d inserted in hash table after %d steps\n\n",  key, steps);
    else      printf("Hash table is full\n\n",  key, steps);
    
    return;
}


void hashtable_show(hashtable hashtbl, int m)
{
     int i=0;
     for(i=0 ; i<m ; i++)
     {    
          printf("+---------------+\n|\t");
          if(hashtbl->array[i]!=-1)
              printf("%d", hashtbl->array[i]);
          else
              printf("\t");
          printf("\t|\n");
     }
     printf("+---------------+\n");
     
     printf("\n");
}


void hashtable_delete( hashtable hashtbl , int key , int m )
{  
    int i=key%m , steps=0 , done = 0;
    
    if(!hashtable_find( hashtbl , key , m ))
         return;
    else
    {
        for( i=key%m ; i<m ; i++)
        {
                steps++;
                if( hashtbl->array[i] == key )
                {
                    hashtbl->array[i] = -1;
                    done = 1;
                    break;
                }         
        }
        
        if(!done)
            for( i=0 ; i<key%m ; i++)
            {
                    steps++;
                    if( hashtbl->array[i] == key )
                    {
                        hashtbl->array[i] = -1;
                        done = 1;
                        break;
                    }          
            }
    }
    
    if(done)    printf("Key %d removed from hash table after %d steps\n\n",  key, steps);
    
    return;
}


int main ( )
{
    char input[15];
    int m=0, key = 0, i=0;
    
    printf("Welcome\n\nGive the size of hash table:  ");
    scanf("%d", &m);
    if(m<=0)
    {
            printf("Invalid hash table size\tAborting...");
            return 1;
    }
    
    printf("Creating hash table...");
    hashtable hashtbl = hash_table_create(m);
    printf("\tDone\n\n");
    
    char delims[] = " ";
    char *result = NULL;
    
    
   
    while (1){
          printf("What would you like to do?\n[help for list of commands]\n");
          
          scanf("%19s", input);
          result = strtok( input, delims );
          
          if(!strcmp("insert", result))
          {
               printf("Give key: ");
               scanf("%d", &key);
               
               hashtable_insert(hashtbl, key , m);
               
               continue;
          }
          
          
          if(!strcmp("search", result))
          {
               printf("Give key: ");
               scanf("%d", &key);
               
               hashtable_find(hashtbl, key , m);
               
               continue;
          }
          
          
          if(!strcmp("insertrand", result))
          {
               printf("How many elements you want to insert random? ");
               scanf("%d", &key);
               
               for(i=0 ; i<key ; i++)
                       hashtable_insert(hashtbl, rand() , m);
               
               continue;
          }
          
          
          if(!strcmp("delete", result))
          {
               printf("Give key: ");
               scanf("%d", &key);
               
               hashtable_delete(hashtbl, key , m);
               
               continue;
          }
          
          
          if(!strcmp("show", result))
          {               
               hashtable_show(hashtbl, m);
               
               continue;
          }
          
          
          if(!strcmp("exit", result))
          {               
               printf("Destroing hash table...\t");
               hashtable_destroy(hashtbl);
               printf("Done\n\n");
               printf("Bye bye!\n\n\n");
               break; 
          }
          
          if(!strcmp("help", result)){
               printf("\nList of commands: \ninsert x \ninsertrand n \nsearch x \ndelete x \nexit \n\n");
               continue;
          }
          
          printf("Please give a valid command\n\n");
    }
    
    //sleep(1000);
    
    return 0;
}
