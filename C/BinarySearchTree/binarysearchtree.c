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
#include<stdlib.h>
#include<stdio.h>

struct tree_el {
   int val;
   struct tree_el * right, * left;
};


typedef struct tree_el node;
node * root;
int search_count;


void insert(node ** tree, node * item) {
   if(!(*tree)) {
      *tree = item;
      return;
   }
   if(item->val<(*tree)->val)
      insert(&(*tree)->left, item);
   else if(item->val>(*tree)->val)
      insert(&(*tree)->right, item);
}


node * findmin(node * temp)
{
	if(temp == NULL || temp->left == NULL)
		return temp;
	return findmin(temp->left);
}


node * deleteval( node * t, int x )
{
    node * temp;
    if( x < t->val )
        t->left = deleteval( t->left, x );
    else if( x > t->val )
        t->right = deleteval( t->right, x );
    else // x == t->val
    {
    	if ( t->left == NULL && t->right == NULL )
    	{
    		t = NULL; 
    	}
        else if ( t->left && t->right )
        {
            temp = findmin(t->right);
            t->val = temp->val;
            t->right = deleteval(t->right, t->val);
        }
        else if (t->left == NULL)
            t = t->right;
        else
            t = t->left;
    }
}


int find(node * t, int x ) {
    //0 not found - 1 found
    search_count++;
    if(t==NULL)    return 0;
	if(x<t->val)   return find(t->left, x);
	if(x>t->val)   return find(t->right, x);
	return 1;
}


int max( node * curr )
{
    while(curr->right != NULL)
         curr = curr->right;
    return curr->val;    
}


int succ(node * t, int x , int curr_max)
{ 
     if( t->val <= x )
     {
          if ( t->val== x && t->right==NULL )
               if (curr_max)
                    return curr_max;
               else 
                    return t->val;  //x = max
               
          if (t->right!=NULL )
               return succ(t->right, x, curr_max);   //go right child
          else
               if (!curr_max)
                    return NULL;
               else 
                    return curr_max;  //x larger than any in tree 
     }     
     else 
     {
          if (t->left!=NULL )
          {
               if( curr_max > t->val && t->val < x)
                    return curr_max;

               curr_max = t->val;
               return succ(t->left, x, curr_max);   
          }
          else 
               if ( curr_max < t->val )
                    return curr_max;
               else
                    return t->val;         
     }         
}


void printout(node * tree) {
   if(tree->left) printout(tree->left);
   printf("%d\n",tree->val);
   if(tree->right) printout(tree->right);
}


void find_print(node * t, int x ) {
    if(t->val==x)    printout(t);
	if(x<t->val)     return find_print(t->left, x);
	if(x>t->val)     return find_print(t->right, x);
}   


void tree_destroy(node * tree)
{
   if(!tree)            return;
   if(tree->left)       tree_destroy(tree->left);
   if(tree->right)      tree_destroy(tree->right);
   //printf("Node %d deleted\n", tree->val);
   free( tree );
}


void makeempty()
{
    root = NULL;     
}


int main() {
    node * curr;

    char input[15];

    printf("Creating binary tree...");
    makeempty();
    printf("\tDone\n\n");
    
    char delims[] = " ";
    char *result = NULL;
    char *item = NULL;

    while (1){
          printf("What would you like to do?\n[help for list of commands]\n");
          gets(input);
          
          search_count = 0;
          
          result = (char *) strtok( input, delims );
          
          if(!strcmp("insert", result))
          {
               item = (char *) strtok( NULL, delims );
               
               if( !find(root, atoi(item)) )
               {
                   curr = (node *)malloc(sizeof(node));
                   curr->left = curr->right = NULL;
                   curr->val = atoi(item);
                   insert(&root, curr);       
                   printout(root);
               }
               else printf("Value %d exists\n\n", atoi(item));
               continue;                          
          }
          
          
          if(!strcmp("delete", result))
          {
               item = (char *) strtok( NULL, delims );
               
               if( find(root, atoi(item)) )
               {
                   printf("Found value %d \n", atoi(item));
                   deleteval( root , atoi(item) );
                   printf("Value %d deleted\n\n", atoi(item));
               }
               else printf("Value %d does not exist\n\n", atoi(item));
               continue;                          
          }
          
          
          if(!strcmp("search", result))
          {
               item = (char *) strtok( NULL, delims );
               
               if( find(root, atoi(item)) )
               {
                   printf("Found value %d - %d nodes walked\n\n", atoi(item), search_count);
               }
               else printf("Value %d does not exist\n\n", atoi(item));
               continue;                          
          }
          
          
          if(!strcmp("max", result))
          {
               if(root != NULL)
                       printf("Max value: %d\n\n", max(root));
               else           printf("Search tree is empty\n\n");
               
               continue;                          
          }
          
          
          if(!strcmp("succ", result))
          {
               item = (char *) strtok( NULL, delims );
               
               int x, tree_max;
               
               if( root == NULL )
               {
                   printf("Search tree is empty\n\n");
                   continue;
               }
               
               x = succ( root , atoi(item) , NULL);
               
               if(x==NULL)
               {
                    printf("Value %d is larger than any other value in tree\n\n", atoi(item));
                    continue;           
                    //printf("Value's %d next larger value is %d\n\n", atoi(item) , x );
               }
               else if ( x == atoi(item) )
               {
                    printf("Value %d is the largest value in tree\n\n", atoi(item));
                    continue;           
                    //printf("Value's %d next larger value is %d\n\n", atoi(item) , x );
               }
               else
               {
                   printf("Value %d is the next larger value of %d\n\n", x , atoi(item));
                   continue;
               }
               continue;                          
          }
          
          
          if(!strcmp("subtree", result)){
                   result = strtok( NULL, delims );
                   if(!strcmp("leaves", result)){
                       item = (char *) strtok( NULL, delims );
                       if( find(root, atoi(item)) )
                       {
                           find_print(root, atoi(item));
                       }
                       else printf("Value %d does not exist\n\n", atoi(item));
                       
                       continue;
                   }
                   else {
                        printf("Please give a valid command\n\n");
                        continue;
                   }
          }
          
          
          if(!strcmp("exit", result)){
               printf("Destroing tree...\t");
               //printf("\n");
               tree_destroy(root);
               printf("Done\n\n");
               printf("Bye bye!\n\n\n");
               break;              
          }
          
          
          if(!strcmp("help", result)){
               printf("\nList of commands: \ninsert x \ndelete x \nsearch x \nmax \nsucc x \nsubtree leaves x \nexit \n\n");
               continue;
          }

          
          printf("Please give a valid command\n\n");
    }
          
    //sleep(1000);
   
    return 0;
}
