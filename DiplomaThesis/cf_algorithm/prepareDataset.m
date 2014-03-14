% 
% =================================
% 
% Univesrity of Patras 
% Computer Engineering and Informatics Department 
% 
% Diploma Thesis 
% Aimed Product Suggestion to Social Network Users 
% 
% Konstantinos Konstantopoulos kkonstanto@ceid.upatras.gr
% 
% =================================
%
% Import raw data from input file
R = dlmread('events.csv', ',');

% Create Item-User matrix
temp = R(1,1);
count = 1;
R(1,1) = count;
for i=2:length(R(:,1))
    if R(i,1)~= temp
        temp = R(i,1);
        count = count+1;
    end
    R(i,1)=count;
end

% R = [R(:,2) R(:,1) R(:,3)];
[~, I] = sort(R(:,2));
R = R(I,:);

temp = R(1,2);
count = 1;
R(1,2) = count;
for i=2:length(R(:,1))
    if R(i,2) ~= temp
        temp = R(i,2);
        count = count+1;
    end
    R(i,2)=count;
end

[~, I] = sort(R(:,1));
R = R(I,:); 

% Final matrix
X = sparse(R(:,2), R(:,1),R(:,3));

